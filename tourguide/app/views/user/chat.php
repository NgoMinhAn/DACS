<?php $realtimeSignature = generate_realtime_signature($booking->id, $currentUserId); ?>
<?php
    $guideName = $booking->guide_name ?? __('chat_ui.guide');
    $guideAvatar = !empty($booking->guide_image) 
        ? url('public/uploads/avatars/' . $booking->guide_image) 
        : 'https://via.placeholder.com/48x48?text=G';
?>

<style>
    .chat-wrapper {
        width: 100%;
        max-width: 900px;
        margin: 0 auto;
        background: #18191a;
        color: #e4e6eb;
        border-radius: 18px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        box-shadow: 0 14px 38px rgba(0,0,0,0.35);
        height: calc(100vh - 170px);
        min-height: 540px;
    }
    .chat-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 24px;
        background: #242526;
        border-bottom: 1px solid rgba(255,255,255,0.08);
    }
    .chat-user {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .chat-user img {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #3a3b3c;
    }
    .chat-username {
        font-weight: 600;
        font-size: 1.05rem;
    }
    .chat-status {
        font-size: 0.85rem;
        color: #b0b3b8;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: color 0.2s ease;
    }
    .chat-status.offline {
        color: #d3d4d8;
    }
    .chat-status.online {
        color: #31a24c;
    }
    .chat-status .status-dot {
        width: 9px;
        height: 9px;
        border-radius: 50%;
        background: #b0b3b8;
        display: inline-block;
    }
    .chat-status .status-dot.online {
        background: #31a24c;
    }
    .chat-body {
        flex: 1;
        padding: 24px 28px;
        background: #18191a;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    .chat-body::-webkit-scrollbar {
        width: 6px;
    }
    .chat-body::-webkit-scrollbar-thumb {
        background: rgba(255,255,255,0.15);
        border-radius: 4px;
    }
    .chat-body::-webkit-scrollbar-track {
        background: transparent;
    }
    .chat-message {
        max-width: 70%;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .chat-message.message-in {
        align-self: flex-start;
        text-align: left;
    }
    .chat-message.message-out {
        align-self: flex-end;
        text-align: right;
    }
    .message-bubble {
        display: inline-block;
        padding: 11px 15px;
        border-radius: 20px;
        font-size: 0.96rem;
        line-height: 1.5;
        word-break: break-word;
        box-shadow: 0 8px 16px rgba(0,0,0,0.25);
    }
    .message-in .message-bubble {
        background: #2f3031;
        color: #e4e6eb;
        border-radius: 20px 20px 20px 8px;
    }
    .message-out .message-bubble {
        background: #8c4dff;
        color: #fff;
        border-radius: 20px 20px 8px 20px;
        box-shadow: 0 8px 20px rgba(140,77,255,0.45);
    }
    .chat-message.message-out .message-bubble {
        margin-left: auto;
    }
    .chat-message.message-in .message-bubble {
        margin-right: auto;
    }
    .message-meta {
        font-size: 0.75rem;
        color: #a8abaf;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .message-in .message-meta {
        align-self: flex-start;
    }
    .message-out .message-meta {
        align-self: flex-end;
    }
    .chat-typing {
        min-height: 20px;
        padding: 0 20px 12px;
        color: #b0b3b8;
        font-size: 0.85rem;
    }
    .chat-input-bar {
        padding: 18px 24px;
        background: #242526;
        border-top: 1px solid rgba(255,255,255,0.08);
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .chat-input-bar input[type="text"] {
        flex: 1;
        min-width: 0;
        background: #3a3b3c;
        border: none;
        color: #e4e6eb;
        height: 52px;
        border-radius: 26px;
        padding: 0 22px;
    }
    .chat-input-bar input::placeholder {
        color: #b0b3b8;
    }
    .chat-input-bar input:focus {
        outline: none;
        box-shadow: 0 0 0 2px rgba(140,77,255,0.35);
    }
    .chat-send-btn {
        flex-shrink: 0;
        background: linear-gradient(135deg, #8c4dff 0%, #a24dff 100%);
        border: none;
        color: #fff;
        border-radius: 50%;
        width: 52px;
        height: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .chat-send-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(140,77,255,0.45);
    }
    .chat-send-btn:focus {
        outline: none;
    }
    .chat-back-link {
        color: #b0b3b8;
        text-decoration: none;
        font-size: 0.9rem;
        transition: color 0.2s ease;
    }
    .chat-back-link:hover {
        color: #fff;
        text-decoration: underline;
    }
    @media (max-width: 768px) {
        .chat-wrapper {
            border-radius: 0;
        height: calc(100vh - 120px);
        }
        .chat-message {
            max-width: 78%;
        }
    }
</style>

<div class="chat-page">
    <a href="<?php echo url('user/bookings'); ?>" class="chat-back-link d-inline-flex align-items-center mb-3">
        <i class="fas fa-arrow-left me-2"></i> <?php echo __('bookings.back_to_list'); ?>
    </a>

    <div class="chat-wrapper">
        <div class="chat-header">
            <div class="chat-user">
                <img src="<?php echo htmlspecialchars($guideAvatar); ?>" alt="<?php echo htmlspecialchars($guideName); ?>">
                <div>
                    <div class="chat-username"><?php echo htmlspecialchars($guideName); ?></div>
                    <div class="chat-status offline" id="presenceStatus">
                        <span class="status-dot" id="presenceDot"></span>
                        <span id="presenceText"><?php echo __('chat.offline'); ?></span>
                    </div>
                </div>
            </div>
            <div class="chat-actions text-muted">
                <i class="fas fa-phone-alt me-3"></i>
                <i class="fas fa-video me-3"></i>
                <i class="fas fa-info-circle"></i>
            </div>
        </div>

        <div class="chat-body" id="chatMessages">
            <?php foreach ($messages as $msg): ?>
                <?php
                    $isCurrentUser = (int) $msg->sender_id === (int) $currentUserId;
                    $statusText = '';
                    if ($isCurrentUser) {
                        if (!empty($msg->read_at)) {
                            $statusText = __('messages.read');
                        } elseif (!empty($msg->delivered_at)) {
                            $statusText = __('messages.sent');
                        } else {
                            $statusText = __('messages.sending');
                        }
                    }
                ?>
                <div class="chat-message <?php echo $isCurrentUser ? 'message-out' : 'message-in'; ?>"
                     data-message-id="<?php echo (int) $msg->id; ?>"
                     data-sender-id="<?php echo (int) $msg->sender_id; ?>"
                     data-delivered="<?php echo !empty($msg->delivered_at) ? '1' : '0'; ?>"
                     data-read="<?php echo !empty($msg->read_at) ? '1' : '0'; ?>">
                    <div class="message-bubble">
                        <?php echo nl2br(htmlspecialchars($msg->message)); ?>
                    </div>
                    <div class="message-meta">
                        <span class="message-time" data-timestamp="<?php echo htmlspecialchars($msg->created_at); ?>">
                            <?php echo gmdate('H:i', strtotime($msg->created_at . ' UTC')); ?>
                        </span>
                        <?php if ($isCurrentUser): ?>
                            <span class="message-status"><?php echo $statusText; ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="chat-typing">
            <span id="typingIndicator" class="d-none"><?php echo htmlspecialchars($guideName . ' ' . __('chat_ui.typing')); ?></span>
        </div>

        <form method="post" class="chat-input-bar">
            <input type="text" name="message" placeholder="<?php echo __('message.placeholder'); ?>" required autocomplete="off">
            <button type="submit" class="chat-send-btn">
                <i class="fas fa-paper-plane"></i>
            </button>
        </form>
    </div>
</div>

<script src="https://cdn.socket.io/4.7.5/socket.io.min.js"></script>
<script>
    (function () {
        const signature = <?php echo $realtimeSignature ? "'" . $realtimeSignature . "'" : 'null'; ?>;
        if (!signature) {
            return;
        }

        const bookingId = <?php echo (int) $booking->id; ?>;
        const currentUserId = <?php echo (int) $currentUserId; ?>;
        const otherUserId = <?php echo isset($booking->guide_user_id) ? (int) $booking->guide_user_id : 'null'; ?>;
        const serverUrl = "<?php echo htmlspecialchars(REALTIME_SERVER_URL, ENT_QUOTES, 'UTF-8'); ?>";
        const socket = io(serverUrl, { transports: ['websocket', 'polling'] });

        const chatMessages = document.getElementById('chatMessages');
        const presenceStatus = document.getElementById('presenceStatus');
        const typingIndicator = document.getElementById('typingIndicator');
        const messageInput = document.querySelector('input[name="message"]');
        const presenceDot = document.getElementById('presenceDot');
        const presenceText = document.getElementById('presenceText');
        const uiLocale = "<?php echo function_exists('getLocale') ? getLocale() : 'en'; ?>";

        const UI_STRINGS = {
            read: "<?php echo addslashes(__('messages.read')); ?>",
            sent: "<?php echo addslashes(__('messages.sent')); ?>",
            sending: "<?php echo addslashes(__('messages.sending')); ?>",
            typing: "<?php echo addslashes($guideName . ' ' . __('chat_ui.typing')); ?>",
            you: "<?php echo addslashes(__('chat_ui.you')); ?>",
            guide: "<?php echo addslashes(__('chat_ui.guide')); ?>",
            presence_online: "<?php echo addslashes(__('chat.online')); ?>",
            presence_offline: "<?php echo addslashes(__('chat.offline')); ?>",
            presence_last_active: "<?php echo addslashes(__('chat.last_active')); ?>"
        };
        let typingTimeout = null;
        let lastTypingState = false;
        const TYPING_DELAY = 3000;
        const deliveredEndpoint = "<?php echo url('messages/mark-delivered'); ?>";
        const readEndpoint = "<?php echo url('messages/mark-read'); ?>";
        const deliveredMessages = new Set();
        const readMessages = new Set();

        if (chatMessages) {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function getTimeElements() {
            if (!chatMessages) return [];
            return chatMessages.querySelectorAll('.message-time');
        }

        function updateRelativeTimes() {
            getTimeElements().forEach((el) => {
                const ts = el.dataset.timestamp;
                if (!ts) return;
                const date = parseUtcTimestamp(ts);
                if (Number.isNaN(date.getTime())) return;
                el.textContent = formatRelativeTime(date);
            });
        }

        const relativeTimeFormatter = new Intl.RelativeTimeFormat(uiLocale, { numeric: 'auto' });

        function formatRelativeTime(date) {
            const now = new Date();
            const diff = (date.getTime() - now.getTime()) / 1000;
            const units = [
                { unit: 'day', value: 86400 },
                { unit: 'hour', value: 3600 },
                { unit: 'minute', value: 60 },
                { unit: 'second', value: 1 },
            ];

            for (const { unit, value } of units) {
                if (Math.abs(diff) >= value || unit === 'second') {
                    const rounded = Math.round(diff / value);
                    return relativeTimeFormatter.format(rounded, unit);
                }
            }
            return date.toLocaleTimeString(uiLocale, { hour: '2-digit', minute: '2-digit', timeZone: 'UTC' });
        }

        function appendMessage(data) {
            if (!data || !chatMessages) return;

            const messageId = Number(data.message_id || data.id);
            if (messageId && chatMessages.querySelector(`.chat-message[data-message-id="${messageId}"]`)) {
                return;
            }

            const isCurrentUser = Number(data.sender_id) === currentUserId;
            const label = isCurrentUser ? UI_STRINGS.you : UI_STRINGS.guide;

            const wrapper = document.createElement('div');
            wrapper.className = 'chat-message ' + (isCurrentUser ? 'message-out' : 'message-in');
            wrapper.dataset.messageId = messageId || '';
            wrapper.dataset.senderId = data.sender_id;
            wrapper.dataset.delivered = data.delivered_at ? '1' : '0';
            wrapper.dataset.read = data.read_at ? '1' : '0';

            const bubble = document.createElement('div');
            bubble.className = 'message-bubble';
            bubble.innerHTML = escapeHtml(data.message).replace(/\n/g, '<br>');
            wrapper.appendChild(bubble);

            if (isCurrentUser) {
                const statusEl = document.createElement('div');
                statusEl.className = 'message-meta';
                statusEl.innerHTML = `<span class="message-time" data-timestamp="${escapeAttribute(data.created_at)}">${formatRelativeOrAbsolute(data.created_at)}</span> <span class="message-status">${getStatusText(data.read_at, data.delivered_at)}</span>`;
                wrapper.appendChild(statusEl);
            } else {
                const meta = document.createElement('div');
                meta.className = 'message-meta';
                meta.innerHTML = `<span class="message-time" data-timestamp="${escapeAttribute(data.created_at)}">${formatRelativeOrAbsolute(data.created_at)}</span>`;
                wrapper.appendChild(meta);
            }

            chatMessages.appendChild(wrapper);
            chatMessages.scrollTop = chatMessages.scrollHeight;

            if (!isCurrentUser && messageId) {
                markDelivered(messageId);
                if (isChatActive()) {
                    markRead(messageId);
                }
            }

            updateRelativeTimes();
        }

        function escapeHtml(text) {
            const span = document.createElement('span');
            span.textContent = text;
            return span.innerHTML;
        }

        function escapeAttribute(text) {
            return text ? text.replace(/"/g, '&quot;') : '';
        }

        function getStatusText(readAt, deliveredAt) {
            if (readAt) {
                return UI_STRINGS.read;
            }
            if (deliveredAt) {
                return UI_STRINGS.sent;
            }
            return UI_STRINGS.sending;
        }

        function parseUtcTimestamp(timestamp) {
            return new Date(timestamp.replace(' ', 'T') + 'Z');
        }

        function formatRelativeOrAbsolute(timestamp) {
            if (!timestamp) return '';
            const date = parseUtcTimestamp(timestamp);
            if (Number.isNaN(date.getTime())) {
                return timestamp;
            }
            const diffMs = Date.now() - date.getTime();
            if (Math.abs(diffMs) < 3600 * 1000) {
                return formatRelativeTime(date);
            }
            return date.toLocaleTimeString(uiLocale, { hour: '2-digit', minute: '2-digit', timeZone: 'UTC' });
        }

        function emitTyping(isTyping) {
            if (!socket.connected) return;
            if (lastTypingState === isTyping) return;

            socket.emit('typing', {
                bookingId: bookingId,
                userId: currentUserId,
                isTyping: isTyping
            });
            lastTypingState = isTyping;
        }

        function handleInput() {
            emitTyping(true);
            if (typingTimeout) {
                clearTimeout(typingTimeout);
            }
            typingTimeout = setTimeout(() => emitTyping(false), TYPING_DELAY);
        }

        function markDelivered(messageId) {
            if (!messageId || deliveredMessages.has(messageId)) return;

            deliveredMessages.add(messageId);

            fetch(deliveredEndpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin',
                body: JSON.stringify({ message_id: messageId })
            }).then((response) => {
                if (!response.ok) {
                    deliveredMessages.delete(messageId);
                }
            }).catch(() => {
                deliveredMessages.delete(messageId);
            });
        }

        function markRead(messageId) {
            if (!messageId || readMessages.has(messageId)) return;

            readMessages.add(messageId);

            fetch(readEndpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin',
                body: JSON.stringify({ message_id: messageId })
            }).then((response) => {
                if (!response.ok) {
                    readMessages.delete(messageId);
                }
            }).catch(() => {
                readMessages.delete(messageId);
            });
        }

        function isAtBottom() {
            if (!chatMessages) return false;
            const threshold = 60;
            return chatMessages.scrollTop + chatMessages.clientHeight >= chatMessages.scrollHeight - threshold;
        }

        function isChatActive() {
            return document.hasFocus() && isAtBottom();
        }

        function initializeExistingMessages() {
            if (!chatMessages) {
                return;
            }

            const existingMessages = chatMessages.querySelectorAll('.chat-message');
            existingMessages.forEach((el) => {
                const messageId = Number(el.dataset.messageId);
                if (!messageId) return;

                if (el.dataset.delivered === '1') {
                    deliveredMessages.add(messageId);
                }
                if (el.dataset.read === '1') {
                    readMessages.add(messageId);
                }

                if (Number(el.dataset.senderId) !== currentUserId) {
                    if (el.dataset.delivered !== '1') {
                        markDelivered(messageId);
                    }
                }
            });

            maybeMarkRead();
        }

        function maybeMarkRead() {
            if (!isChatActive()) {
                return;
            }

            const incomingMessages = Array.from(chatMessages.querySelectorAll('.chat-message')).filter((el) => Number(el.dataset.senderId) !== currentUserId);
            incomingMessages.forEach((el) => {
                const messageId = Number(el.dataset.messageId);
                if (!messageId || readMessages.has(messageId)) return;
                markDelivered(messageId);
                markRead(messageId);
            });
        }

        if (messageInput) {
            messageInput.addEventListener('input', handleInput);
            messageInput.addEventListener('focus', handleInput);
            messageInput.addEventListener('blur', () => emitTyping(false));
            if (messageInput.form) {
                messageInput.form.addEventListener('submit', () => emitTyping(false));
            }
        }

        if (chatMessages) {
            chatMessages.addEventListener('scroll', () => {
                if (isAtBottom()) {
                    maybeMarkRead();
                }
            });
        }

        window.addEventListener('focus', maybeMarkRead);
        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'visible') {
                maybeMarkRead();
            }
        });

        function setPresence(isOnline, lastActive = null) {
            if (!presenceStatus) return;
            if (presenceDot) {
                presenceDot.classList.toggle('online', isOnline);
            }
            if (presenceText) {
                presenceText.textContent = isOnline
                    ? UI_STRINGS.presence_online
                    : UI_STRINGS.presence_offline;
            }
            presenceStatus.classList.toggle('online', isOnline);
            presenceStatus.classList.toggle('offline', !isOnline);

            if (!isOnline && lastActive) {
                presenceStatus.setAttribute('title', UI_STRINGS.presence_last_active + ' ' + lastActive);
            } else {
                presenceStatus.removeAttribute('title');
            }
        }

        setPresence(false);

        socket.on('connect', () => {
            console.log('[Chat] Socket connected successfully!');
        });

        socket.on('connect_error', (error) => {
            console.error('[Chat] Connection error:', error);
            console.warn('Realtime server unavailable. Falling back to manual refresh.');
        });

        socket.on('disconnect', (reason) => {
            console.log('[Chat] Socket disconnected:', reason);
        });

        console.log('[Chat] Joining room - Booking:', bookingId, 'User:', currentUserId);
        socket.emit('join', {
            bookingId: bookingId,
            userId: currentUserId,
            signature: signature
        });

        socket.on('message', (data) => {
            console.log('[Chat] New message received:', data);
            appendMessage(data);
        });

        socket.on('messageStatus', (payload) => {
            if (!payload) return;

            const messageId = Number(payload.message_id);
            if (!messageId) return;

            const messageElement = chatMessages ? chatMessages.querySelector(`.chat-message[data-message-id="${messageId}"]`) : null;
            if (!messageElement) return;

            const isCurrentUserSender = Number(messageElement.dataset.senderId) === currentUserId;
            if (!isCurrentUserSender) return;

            const statusElement = messageElement.querySelector('.message-status');
            if (!statusElement) return;

            if (payload.status === 'delivered') {
                messageElement.dataset.delivered = '1';
                statusElement.textContent = UI_STRINGS.sent;
            } else if (payload.status === 'read') {
                messageElement.dataset.delivered = '1';
                messageElement.dataset.read = '1';
                statusElement.textContent = UI_STRINGS.read;
            }
        });

        socket.on('typing', ({ userId, isTyping }) => {
            if (!typingIndicator) return;
            if (Number(userId) === currentUserId) return;

            typingIndicator.classList.toggle('d-none', !isTyping);
        });

        socket.on('disconnect', () => {
            if (typingIndicator) {
                typingIndicator.classList.add('d-none');
            }
            setPresence(false);
        });

        socket.on('presence', (payload) => {
            console.log('[Chat] Presence update:', payload, 'Looking for user:', otherUserId);
            if (!payload || !presenceStatus || !otherUserId) return;
            const participant = (payload.users || []).find((user) => Number(user.userId) === otherUserId);
            console.log('[Chat] Found participant:', participant);
            if (!participant) {
                setPresence(false);
                return;
            }

            setPresence(Boolean(participant.online), participant.lastActive || null);
        });

        initializeExistingMessages();
        updateRelativeTimes();
        setInterval(updateRelativeTimes, 60 * 1000);
    })();
</script>