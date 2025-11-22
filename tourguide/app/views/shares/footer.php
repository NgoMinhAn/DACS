    </div><!-- Close main container -->

    <!-- Footer -->
    <footer class="bg-dark text-white py-5 mt-auto">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <img src="<?php echo url('public/img/logo.png'); ?>" alt="<?php echo SITE_NAME; ?>" style="height: 40px; width: auto; margin-bottom: 1rem; filter: brightness(0) invert(1);">
                    <p class="text-white">Connecting travelers with expert local guides worldwide.</p>
                    <div class="social-links mt-3">
                        <a href="<?php echo getConfig('social_media.facebook'); ?>" class="text-white me-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="<?php echo getConfig('social_media.twitter'); ?>" class="text-white me-2"><i class="fab fa-twitter"></i></a>
                        <a href="<?php echo getConfig('social_media.instagram'); ?>" class="text-white me-2"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-4 mb-md-0">
                    <h6><?php echo __('footer.guides'); ?></h6>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo url('tourGuide/browse'); ?>" class="text-white text-decoration-none"><?php echo __('nav.find_guide'); ?></a></li>
                        <li><a href="<?php echo url('tourGuide/categories'); ?>" class="text-white text-decoration-none"><?php echo __('nav.guide_categories'); ?></a></li>
                        <li><a href="<?php echo url('account/register/guide'); ?>" class="text-white text-decoration-none "><?php echo __('nav.become_guide'); ?></a></li>
                        <li><a href="<?php echo url('guides/how-it-works'); ?>" class="text-white text-decoration-none"><?php echo __('footer.how_it_works'); ?></a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4 mb-md-0">
                    <h6><?php echo __('footer.company'); ?></h6>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo url('about'); ?>" class="text-white text-decoration-none"><?php echo __('nav.about'); ?></a></li>
                        <li><a href="<?php echo url('careers'); ?>" class="text-white text-decoration-none"><?php echo __('footer.careers'); ?></a></li>
                        <li><a href="<?php echo url('blog'); ?>" class="text-white text-decoration-none"><?php echo __('footer.blog'); ?></a></li>
                        <li><a href="<?php echo url('contact'); ?>" class="text-white text-decoration-none"><?php echo __('nav.contact'); ?></a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6><?php echo __('footer.contact_us'); ?></h6>
                    <address class="text-white">
                        <i class="fas fa-map-marker-alt me-2"></i> <?php echo getConfig('contact.address'); ?><br>
                        <i class="fas fa-phone me-2"></i> <?php echo getConfig('contact.phone'); ?><br>
                        <i class="fas fa-envelope me-2"></i> <a href="mailto:<?php echo getConfig('contact.email'); ?>" class="text-white text-decoration-none"><?php echo getConfig('contact.email'); ?></a>
                    </address>
                </div>
            </div>
            <hr class="my-4">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="small text-white text-decoration-none mb-0">&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <ul class="list-inline small text-white mb-0">
                        <li class="list-inline-item"><a href="<?php echo url('terms'); ?>" class="text-white text-decoration-none"><?php echo __('footer.terms'); ?></a></li>
                        <li class="list-inline-item">|</li>
                        <li class="list-inline-item"><a href="<?php echo url('privacy'); ?>" class="text-white text-decoration-none"><?php echo __('footer.privacy'); ?></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script src="<?php echo url('public/js/main.js'); ?>"></script>

    <!-- Map Floating Button -->
    <button type="button" class="btn btn-success rounded-circle shadow position-fixed"
            style="bottom: 120px; right: 20px; width: 56px; height: 56px; z-index: 1050;"
            data-bs-toggle="modal" data-bs-target="#mapModal" title="<?php echo __('footer.map_button'); ?>">
        <i class="fas fa-map-marked-alt fa-lg"></i>
    </button>

    <!-- Map Modal -->
    <div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="mapModalLabel"><?php echo __('contact.our_location'); ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php echo __('buttons.close'); ?>"></button>
                    </div>
          <div class="modal-body p-0">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.657600383133!2d105.78236757374515!3d21.046398484339835!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab323f3a20f1%3A0x4898724834e6958!2sHanoi%20University%20of%20Science%20and%20Technology!5e0!3m2!1sen!2s!4v1715167113971!5m2!1sen!2s"
              width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
          </div>
        </div>
      </div>
    </div>

    <!-- Floating AI Chat Button & Popup (bottom-left) -->
    <div id="ai-chat-root">
        <button id="ai-chat-button" aria-label="<?php echo __('chat.open') ?? 'Open chat'; ?>">
            <i class="fas fa-robot"></i>
        </button>

        <div id="ai-chat-panel" aria-hidden="true">
            <!-- Header with back arrow, avatar, name, status, menu and close -->
            <div class="ai-chat-header d-flex align-items-center px-3 py-3" style="background: #fff; border-bottom: 1px solid #e5e7eb;">
                <button id="ai-chat-back" class="btn btn-link p-0 me-2" style="border: none; color: #000;">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <div class="ai-chat-avatar me-2" style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: bold;">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center">
                        <span class="fw-bold" style="font-size: 1rem; color: #111;"><?php echo SITE_NAME; ?> AI Assistant</span>
                        <span class="status-dot-online ms-2" style="width: 8px; height: 8px; background: #10b981; border-radius: 50%; display: inline-block;"></span>
                    </div>
                    <div style="font-size: 0.75rem; color: #6b7280;">AI Agent</div>
                </div>
                <button id="ai-chat-menu" class="btn btn-link p-0 me-2" style="border: none; color: #6b7280;">
                    <i class="fas fa-ellipsis-h"></i>
                </button>
                <button id="ai-chat-close" class="btn btn-link p-0" style="border: none; color: #6b7280;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Messages Area -->
            <div id="ai-chat-messages" class="p-4" style="height: 550px; overflow-y: auto; background: #fff;">
                <!-- Messages will be inserted here -->
            </div>
            
            <!-- Input Area with send button inside -->
            <form id="ai-chat-form" class="p-3" style="background: #fff; border-top: 1px solid #e5e7eb;">
                <div class="position-relative">
                    <input type="text" id="ai-chat-input" name="message" 
                           class="form-control" 
                           placeholder="Write your message" 
                           autocomplete="off" required 
                           style="padding-right: 50px; border-radius: 24px; border: 1px solid #e5e7eb; padding-left: 16px; padding-top: 12px; padding-bottom: 12px; font-size: 0.95rem;" />
                    <button type="submit" class="btn position-absolute" 
                            style="right: 4px; top: 50%; transform: translateY(-50%); width: 40px; height: 40px; border-radius: 50%; background: #8b5cf6; border: none; color: #fff; display: flex; align-items: center; justify-content: center; padding: 0;">
                        <i class="fas fa-arrow-up"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
    /* Floating chat button/panel (bottom-left) */
    #ai-chat-root { position: fixed; left: 20px; bottom: 20px; z-index: 1200; }
    #ai-chat-button { 
        width: 56px; 
        height: 56px; 
        border-radius: 50%; 
        background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); 
        color: #fff !important; 
        border: none; 
        box-shadow: 0 8px 24px rgba(74,85,104,0.3); 
        display: flex !important; 
        align-items: center; 
        justify-content: center; 
        font-size: 20px; 
        cursor: pointer; 
        transition: transform 0.2s ease; 
    }
    #ai-chat-button i { 
        color: #fff !important; 
        font-size: 20px !important; 
    }
    #ai-chat-button:hover { transform: scale(1.05); }
    #ai-chat-button:focus { outline: none; box-shadow: 0 8px 30px rgba(13,110,253,0.35); }
    #ai-chat-panel { width: 380px; max-width: calc(100vw - 40px); background: #fff; border-radius: 16px 16px 0 0; box-shadow: 0 20px 60px rgba(0,0,0,0.15); overflow: hidden; margin-bottom: 0; display: none; max-height: 600px; }
    #ai-chat-panel.open { display: flex; flex-direction: column; }
    #ai-chat-messages { flex: 1; }
    /* Custom scrollbar for messages area */
    #ai-chat-messages::-webkit-scrollbar { width: 6px; }
    #ai-chat-messages::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
    #ai-chat-messages::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; }
    #ai-chat-messages::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
    #ai-chat-messages .msg { margin-bottom: 12px; }
    #ai-chat-messages .msg.user { text-align: right; }
    #ai-chat-messages .msg .bubble { display: inline-block; padding: 10px 14px; border-radius: 18px; max-width: 75%; word-wrap: break-word; }
    #ai-chat-messages .msg.user .bubble { background: #3b82f6; color: #fff; }
    #ai-chat-messages .msg.ai .bubble { background: #f3f4f6; color: #111; }
    #ai-chat-input:focus { outline: none; border-color: #8b5cf6 !important; box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1); }
    #ai-chat-form button[type="submit"]:hover { background: #7c3aed !important; }
    </style>

    <script>
    // Chat pop-up behavior
    document.addEventListener('DOMContentLoaded', function() {
        const aiBtn = document.getElementById('ai-chat-button');
        const aiPanel = document.getElementById('ai-chat-panel');
        const aiClose = document.getElementById('ai-chat-close');
        const aiForm = document.getElementById('ai-chat-form');
        const aiInput = document.getElementById('ai-chat-input');
        const aiMessages = document.getElementById('ai-chat-messages');

        if (!aiBtn || !aiPanel) return; // Exit if elements don't exist

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

        const aiBack = document.getElementById('ai-chat-back');
        if (aiBack) {
            aiBack.addEventListener('click', () => {
                aiPanel.classList.remove('open');
                aiPanel.setAttribute('aria-hidden', 'true');
            });
        }

        if (aiClose) {
            aiClose.addEventListener('click', () => {
                aiPanel.classList.remove('open');
                aiPanel.setAttribute('aria-hidden', 'true');
            });
        }

        if (aiForm) {
            aiForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const msg = aiInput.value.trim();
                if (!msg) return;
                appendAiMessage('user', msg);
                aiInput.value = '';
                appendAiMessage('ai', '<em>Thinking...</em>');

                fetch('<?php echo url('chatbot.php'); ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ message: msg })
                })
                .then(res => res.json())
                .then(data => {
                    const last = aiMessages.querySelector('.msg.ai:last-child .bubble');
                    if (last) last.innerHTML = data.reply;
                })
                .catch(() => {
                    const last = aiMessages.querySelector('.msg.ai:last-child .bubble');
                    if (last) last.innerHTML = '<span class="text-danger">Error contacting AI.</span>';
                });
            });
        }
    });
    </script>

    <!-- Back to Top Button -->
    <button id="backToTop" class="btn btn-primary rounded-circle shadow position-fixed" 
            style="bottom: 30px; right: 30px; width: 36px; height: 36px; display: none; z-index: 1050;"
            title="<?php echo __('footer.back_to_top'); ?>">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Custom JavaScript for Back to Top -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const backToTopButton = document.getElementById('backToTop');
        
        // Hiển thị nút khi cuộn xuống 300px
        window.onscroll = function() {
            if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
                backToTopButton.style.display = "block";
            } else {
                backToTopButton.style.display = "none";
            }
        };
        
       // Scroll to top of page when button is clicked
        backToTopButton.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    });
    </script>
</body>
</html>
    
