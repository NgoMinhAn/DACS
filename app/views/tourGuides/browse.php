<!-- AI Chatbot for Tour Recommendations -->
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-robot me-2"></i>Ask the AI for Tour Recommendations
                </div>
                <div class="card-body">
                    <div id="chatbot-messages" style="height: 250px; overflow-y: auto; background: #f8f9fa; border-radius: 6px; padding: 10px; margin-bottom: 1rem;"></div>
                    <form id="chatbot-form" class="d-flex">
                        <input type="text" id="chatbot-input" class="form-control me-2" placeholder="Ask for tour recommendations..." autocomplete="off" required />
                        <button type="submit" class="btn btn-primary">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
const chatForm = document.getElementById('chatbot-form');
const chatInput = document.getElementById('chatbot-input');
const chatMessages = document.getElementById('chatbot-messages');

function appendMessage(sender, text) {
    const msgDiv = document.createElement('div');
    msgDiv.innerHTML = `<strong>${sender}:</strong> ${text}`;
    msgDiv.style.marginBottom = '8px';
    chatMessages.appendChild(msgDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

chatForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const userMsg = chatInput.value.trim();
    if (!userMsg) return;
    appendMessage('You', userMsg);
    chatInput.value = '';
    appendMessage('AI', '<em>Thinking...</em>');
    fetch('/chatbot.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ message: userMsg })
    })
    .then(res => res.json())
    .then(data => {
        chatMessages.lastChild.innerHTML = `<strong>AI:</strong> ${data.reply}`;
    })
    .catch(() => {
        chatMessages.lastChild.innerHTML = `<strong>AI:</strong> <span class='text-danger'>Error contacting AI.</span>`;
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
            <!-- Recommended Section -->
            <?php if (!empty($recommended_guides)): ?>
                <div class="mb-4">
                    <h4>Recommended for you</h4>
                    <div class="row row-cols-1 row-cols-md-3 g-4">
                        <?php foreach ($recommended_guides as $rguide): ?>
                            <div class="col">
                                <div class="card h-100">
                                    <img src="<?php echo url('assets/images/profiles/' . ($rguide->profile_image ?? 'default.jpg')); ?>" 
                                         class="card-img-top" alt="<?php echo htmlspecialchars($rguide->name ?? $rguide->name); ?>"
                                         style="height: 150px; object-fit: cover;">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($rguide->name ?? ($rguide->name ?? 'Guide')); ?></h5>
                                        <div class="mb-2">
                                            <span class="text-warning">
                                                <?php for($i = 1; $i <= 5; $i++): ?>
                                                    <?php if($i <= round($rguide->avg_rating ?? 0)): ?>
                                                        <i class="fas fa-star"></i>
                                                    <?php else: ?>
                                                        <i class="far fa-star"></i>
                                                    <?php endif; ?>
                                                <?php endfor; ?>
                                            </span>
                                            <small class="text-muted">(<?php echo $rguide->total_reviews ?? 0; ?> reviews)</small>
                                        </div>
                                        <p class="card-text"><?php echo htmlspecialchars(substr($rguide->bio ?? 'Expert local guide', 0, 80)) . '...'; ?></p>
                                    </div>
                                    <div class="card-footer bg-transparent border-top-0">
                                        <a href="<?php echo url('tourGuide/profile/' . ($rguide->guide_id ?? $rguide->id ?? '')); ?>" class="btn btn-outline-primary w-100">View Profile</a>
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