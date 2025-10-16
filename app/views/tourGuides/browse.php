<!-- Floating AI Chat Button & Popup (bottom-left) -->
<div id="ai-chat-root">
    <button id="ai-chat-button" aria-label="Open chat">
        <i class="fas fa-robot"></i>
    </button>

    <div id="ai-chat-panel" aria-hidden="true">
        <div class="ai-chat-header bg-primary text-white d-flex justify-content-between align-items-center px-3 py-2">
            <div><i class="fas fa-robot me-2"></i>Tour AI</div>
            <button id="ai-chat-close" class="btn btn-sm btn-light">Close</button>
        </div>
        <div id="ai-chat-messages" class="p-3" style="height: 260px; overflow-y:auto; background:#fbfbff;">
        </div>
        <form id="ai-chat-form" class="d-flex p-3" action="/chatbot.php" method="POST">
            <input type="text" id="ai-chat-input" name="message" class="form-control me-2" placeholder="Ask for tour recommendations..." autocomplete="off" required />
            <button type="submit" class="btn btn-primary">Send</button>
        </form>
    </div>
</div>

<style>
/* Floating chat button/panel (bottom-left) */
#ai-chat-root { position: fixed; left: 20px; bottom: 20px; z-index: 1200; }
#ai-chat-button { width: 56px; height: 56px; border-radius: 50%; background: linear-gradient(135deg,#0d6efd,#6610f2); color: #fff; border: none; box-shadow: 0 8px 24px rgba(13,110,253,0.25); display:flex; align-items:center; justify-content:center; font-size:20px; }
#ai-chat-button:focus { outline: none; box-shadow: 0 8px 30px rgba(13,110,253,0.35); }
#ai-chat-panel { width: 340px; max-width: calc(100vw - 40px); background: #fff; border-radius: 10px; box-shadow: 0 20px 60px rgba(2,6,23,0.2); overflow: hidden; margin-bottom: 12px; display: none; }
.ai-chat-header { font-weight:600; }
#ai-chat-panel.open { display: block; }
#ai-chat-messages .msg { margin-bottom:10px; }
#ai-chat-messages .msg.user { text-align: right; }
#ai-chat-messages .msg .bubble { display:inline-block; padding:8px 12px; border-radius: 14px; max-width:80%; }
#ai-chat-messages .msg.user .bubble { background:#cfe9ff; }
#ai-chat-messages .msg.ai .bubble { background:#f1f4ff; }
</style>

<script>
// Chat pop-up behavior
const aiBtn = document.getElementById('ai-chat-button');
const aiPanel = document.getElementById('ai-chat-panel');
const aiClose = document.getElementById('ai-chat-close');
const aiForm = document.getElementById('ai-chat-form');
const aiInput = document.getElementById('ai-chat-input');
const aiMessages = document.getElementById('ai-chat-messages');

function appendAiMessage(kind, text) {
    const div = document.createElement('div');
    div.className = 'msg ' + kind;
    const bubble = document.createElement('div');
    bubble.className = 'bubble';
    bubble.innerHTML = text;
    div.appendChild(bubble);
    aiMessages.appendChild(div);
    aiMessages.scrollTop = aiMessages.scrollHeight;
}

aiBtn.addEventListener('click', () => {
    aiPanel.classList.toggle('open');
    aiPanel.setAttribute('aria-hidden', aiPanel.classList.contains('open') ? 'false' : 'true');
});
aiClose.addEventListener('click', () => {
    aiPanel.classList.remove('open');
    aiPanel.setAttribute('aria-hidden', 'true');
});

aiForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const msg = aiInput.value.trim();
    if (!msg) return;
    appendAiMessage('user', msg);
    aiInput.value = '';
    appendAiMessage('ai', '<em>Thinking...</em>');

    fetch('/chatbot.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ message: msg })
    })
    .then(res => res.json())
    .then(data => {
        // replace last AI thinking bubble
        const last = aiMessages.querySelector('.msg.ai:last-child .bubble');
        if (last) last.innerHTML = data.reply;
    })
    .catch(() => {
        const last = aiMessages.querySelector('.msg.ai:last-child .bubble');
        if (last) last.innerHTML = '<span class="text-danger">Error contacting AI.</span>';
    });
});
</script>
<?php
/**
 * Browse Tour Guides View
 * Displays a grid of available tour guides with filtering options
 */
?>

<div class="container my-5">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-4"><?php echo $title; ?></h1>
            <p class="lead">Find your perfect guide for an unforgettable experience</p>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Filters</h5>
                    <form method="GET" action="<?php echo url('tourGuide/browse'); ?>">
                        <!-- Language Filter -->
                        <div class="mb-3">
                            <label class="form-label">Languages</label>
                            <select class="form-select" name="language">
                                <option value="">All Languages</option>
                                <?php foreach ($languages as $language): ?>
                                    <option value="<?php echo $language->code; ?>" <?php echo (isset($current_filters['language']) && $current_filters['language'] === $language->code) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($language->name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Specialty Filter -->
                        <div class="mb-3">
                            <label class="form-label">Specialty</label>
                            <select class="form-select" name="specialty">
                                <option value="">All Specialties</option>
                                <?php foreach ($specialties as $specialty): ?>
                                    <option value="<?php echo $specialty->id; ?>" <?php echo (isset($current_filters['specialty']) && $current_filters['specialty'] == $specialty->id) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($specialty->name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Price Range Filter -->
                        <div class="mb-3">
                            <label class="form-label">Price Range (per hour)</label>
                            <select class="form-select" name="price_range">
                                <option value="">Any Price</option>
                                <option value="0-25" <?php echo (isset($current_filters['price_range']) && $current_filters['price_range'] === '0-25') ? 'selected' : ''; ?>>Under $25</option>
                                <option value="25-50" <?php echo (isset($current_filters['price_range']) && $current_filters['price_range'] === '25-50') ? 'selected' : ''; ?>>$25 - $50</option>
                                <option value="50-100" <?php echo (isset($current_filters['price_range']) && $current_filters['price_range'] === '50-100') ? 'selected' : ''; ?>>$50 - $100</option>
                                <option value="100+" <?php echo (isset($current_filters['price_range']) && $current_filters['price_range'] === '100+') ? 'selected' : ''; ?>>$100+</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                        
                        <?php if (!empty($current_filters)): ?>
                            <a href="<?php echo url('tourGuide/browse'); ?>" class="btn btn-outline-secondary w-100 mt-2">Clear Filters</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>

        <!-- Guide Cards -->
        <div class="col-md-9">
            <!-- Recommended Section (highlighted) -->
            <?php if (!empty($recommended_guides)): ?>
                <div class="mb-4 recommend-highlight p-3 rounded-3">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h4 class="mb-0">Recommended for you</h4>
                            <small class="text-muted">Based on your interests and past activity</small>
                        </div>
                        <div>
                            <span class="badge bg-primary fs-6 recommendation-pill">Top Picks</span>
                        </div>
                    </div>

                    <div class="recommended-scroller d-flex gap-3 py-2">
                        <?php foreach ($recommended_guides as $rguide): ?>
                            <div class="card recommend-card flex-shrink-0" style="width: 260px;">
                                <div class="ribbon">Recommended</div>
                                <img src="<?php echo url('assets/images/profiles/' . ($rguide->profile_image ?? 'default.jpg')); ?>" 
                                     class="card-img-top" alt="<?php echo htmlspecialchars($rguide->name ?? $rguide->name); ?>"
                                     style="height: 160px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title mb-1"><?php echo htmlspecialchars($rguide->name ?? ($rguide->name ?? 'Guide')); ?></h5>
                                    <div class="mb-2 small">
                                        <span class="text-warning">
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <?php if($i <= round($rguide->avg_rating ?? 0)): ?>
                                                    <i class="fas fa-star"></i>
                                                <?php else: ?>
                                                    <i class="far fa-star"></i>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </span>
                                        <small class="text-muted"> (<?php echo $rguide->total_reviews ?? 0; ?>)</small>
                                    </div>
                                    <p class="card-text small mb-2"><?php echo htmlspecialchars(substr($rguide->bio ?? 'Expert local guide', 0, 80)) . '...'; ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-primary fw-bold">$<?php echo number_format($rguide->hourly_rate ?? 0, 2); ?>/hr</span>
                                        <a href="<?php echo url('tourGuide/profile/' . ($rguide->guide_id ?? $rguide->id ?? '')); ?>" class="btn btn-sm btn-outline-primary">View</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <!-- Separator between recommendations and full browse list -->
                <div class="my-4">
                    <hr class="my-0" />
                </div>
            <?php endif; ?>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php if (!empty($guides)): ?>
                    <?php foreach ($guides as $guide): ?>
                        <div class="col">
                            <div class="card h-100">
                                <img src="<?php echo url('assets/images/profiles/' . ($guide->profile_image ?? 'default.jpg')); ?>" 
                                     class="card-img-top" alt="<?php echo htmlspecialchars($guide->name); ?>"
                                     style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($guide->name); ?></h5>
                                    <div class="mb-2">
                                        <span class="text-warning">
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <?php if($i <= round($guide->avg_rating)): ?>
                                                    <i class="fas fa-star"></i>
                                                <?php else: ?>
                                                    <i class="far fa-star"></i>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </span>
                                        <small class="text-muted">(<?php echo $guide->total_reviews; ?> <?php echo $guide->total_reviews == 1 ? 'review' : 'reviews'; ?>)</small>
                                    </div>
                                    <p class="card-text"><?php echo htmlspecialchars(substr($guide->bio ?? 'Expert local guide', 0, 100)) . '...'; ?></p>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            <i class="fas fa-map-marker-alt me-1"></i> <?php echo htmlspecialchars($guide->location ?? 'Local area'); ?>
                                        </small>
                                    </p>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            <i class="fas fa-tag me-1"></i> $<?php echo number_format($guide->hourly_rate ?? 0, 2); ?>/hour
                                        </small>
                                    </p>
                                    <?php if (!empty($guide->specialties)): ?>
                                        <p class="card-text">
                                            <small class="text-muted">
                                                <i class="fas fa-certificate me-1"></i> <?php echo htmlspecialchars($guide->specialties); ?>
                                            </small>
                                        </p>
                                    <?php endif; ?>
                                </div>
                                <div class="card-footer bg-transparent border-top-0">
                                    <a href="<?php echo url('tourGuide/profile/' . $guide->guide_id); ?>" class="btn btn-outline-primary w-100">View Profile</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info" role="alert">
                            <i class="fas fa-info-circle me-2"></i> No tour guides found matching your criteria. Try adjusting your filters.
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div> 