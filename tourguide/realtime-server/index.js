import http from 'http';
import crypto from 'crypto';

import cors from 'cors';
import dotenv from 'dotenv';
import express from 'express';
import { Server } from 'socket.io';

dotenv.config();

const app = express();
const server = http.createServer(app);

const {
  PORT = 3001,
  ALLOWED_ORIGINS = 'http://localhost',
  REALTIME_AUTH_SECRET,
  REALTIME_BROADCAST_TOKEN,
} = process.env;

if (!REALTIME_AUTH_SECRET || !REALTIME_BROADCAST_TOKEN) {
  console.warn(
    '[Realtime] Warning: REALTIME_AUTH_SECRET or REALTIME_BROADCAST_TOKEN missing. Update your environment variables.'
  );
}

const allowedOrigins = ALLOWED_ORIGINS.split(',').map((origin) => origin.trim());

app.use(cors());
app.use(express.json());

const io = new Server(server, {
  cors: {
    origin: (origin, callback) => {
      // Allow same-origin requests (no Origin header) and configured origins
      if (!origin || allowedOrigins.includes(origin)) {
        callback(null, true);
      } else {
        callback(new Error('Origin not allowed by CORS'));
      }
    },
    credentials: true,
  },
});

const bookingPresence = new Map(); // Map<bookingId, Map<userId, connectionCount>>
const bookingParticipants = new Map(); // Map<bookingId, Set<userId>>
const lastActive = new Map(); // Map<userId, ISO timestamp>

function verifySignature(bookingId, userId, signature) {
  if (!REALTIME_AUTH_SECRET || !signature) {
    return false;
  }

  const payload = `${bookingId}|${userId}`;
  const expectedHex = crypto
    .createHmac('sha256', REALTIME_AUTH_SECRET)
    .update(payload)
    .digest('hex');

  try {
    const providedBuffer = Buffer.from(signature, 'hex');
    const expectedBuffer = Buffer.from(expectedHex, 'hex');

    if (providedBuffer.length !== expectedBuffer.length) {
      return false;
    }

    return crypto.timingSafeEqual(providedBuffer, expectedBuffer);
  } catch (err) {
    return false;
  }
}

function markOnline(bookingId, userId) {
  const roomKey = String(bookingId);
  const participantKey = String(userId);

  const presenceMap = bookingPresence.get(roomKey) || new Map();
  const currentCount = presenceMap.get(participantKey) || 0;
  presenceMap.set(participantKey, currentCount + 1);
  bookingPresence.set(roomKey, presenceMap);

  const participants = bookingParticipants.get(roomKey) || new Set();
  participants.add(participantKey);
  bookingParticipants.set(roomKey, participants);

  broadcastPresence(bookingId);
}

function markOffline(bookingId, userId) {
  const roomKey = String(bookingId);
  const participantKey = String(userId);

  const presenceMap = bookingPresence.get(roomKey);
  if (!presenceMap) {
    return;
  }

  const currentCount = presenceMap.get(participantKey) || 0;
  if (currentCount <= 1) {
    presenceMap.delete(participantKey);
    lastActive.set(participantKey, new Date().toISOString());
  } else {
    presenceMap.set(participantKey, currentCount - 1);
  }

  if (presenceMap.size === 0) {
    bookingPresence.delete(roomKey);
  } else {
    bookingPresence.set(roomKey, presenceMap);
  }

  broadcastPresence(bookingId);
}

function broadcastPresence(bookingId) {
  const roomKey = String(bookingId);
  const presenceMap = bookingPresence.get(roomKey) || new Map();
  const participants = bookingParticipants.get(roomKey) || new Set();

  const userKeys = new Set([...participants, ...presenceMap.keys()]);
  const users = Array.from(userKeys).map((participantKey) => {
    const online = (presenceMap.get(participantKey) || 0) > 0;
    return {
      userId: Number(participantKey),
      online,
      lastActive: online ? null : lastActive.get(participantKey) || null,
    };
  });

  io.to(`booking-${bookingId}`).emit('presence', {
    bookingId: Number(bookingId),
    users,
    timestamp: new Date().toISOString(),
  });
}

io.on('connection', (socket) => {
  socket.on('join', ({ bookingId, userId, signature }) => {
    if (!bookingId || !userId || !verifySignature(bookingId, userId, signature)) {
      socket.emit('error', { message: 'Invalid realtime credentials.' });
      socket.disconnect();
      return;
    }

    const room = `booking-${bookingId}`;
    socket.join(room);
    socket.data.bookingId = bookingId;
    socket.data.userId = userId;

    markOnline(bookingId, userId);
  });

  socket.on('typing', ({ bookingId, userId, isTyping }) => {
    if (!bookingId || !userId) {
      return;
    }

    const room = `booking-${bookingId}`;
    socket.to(room).emit('typing', {
      userId,
      isTyping: Boolean(isTyping),
    });
  });

  socket.on('disconnect', () => {
    const { bookingId, userId } = socket.data || {};
    if (bookingId && userId) {
      markOffline(bookingId, userId);
    }
  });
});

app.post('/broadcast', (req, res) => {
  const authHeader = req.headers['authorization'] || '';
  const token = authHeader.replace('Bearer ', '');

  if (!REALTIME_BROADCAST_TOKEN || token !== REALTIME_BROADCAST_TOKEN) {
    return res.status(401).json({ success: false, message: 'Unauthorized' });
  }

  const { bookingId, event = 'message', data } = req.body || {};

  if (!bookingId || !data) {
    return res.status(400).json({ success: false, message: 'Invalid payload' });
  }

  io.to(`booking-${bookingId}`).emit(event, data);

  return res.json({ success: true });
});

server.listen(PORT, () => {
  console.log(`[Realtime] Socket.IO server running on port ${PORT}`);
});

