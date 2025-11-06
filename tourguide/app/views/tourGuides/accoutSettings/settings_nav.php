<div class="card border-0 shadow-lg rounded-4 mb-4">
    <div class="list-group list-group-flush">
        <?php
            $uri = $_SERVER['REQUEST_URI'] ?? '';
            $isGeneral = (strpos($uri, 'account-settings') !== false && strpos($uri, 'profile') === false && strpos($uri, 'password') === false);
            $isProfile = (strpos($uri, 'profile') !== false);
            $isPassword = (strpos($uri, 'password') !== false);
        ?>
        <a href="<?php echo url('guide/account-settings'); ?>" class="list-group-item list-group-item-action rounded-3 m-2 <?php echo $isGeneral ? 'active' : ''; ?>" style="<?php echo $isGeneral ? 'background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); color: white; border: none;' : ''; ?>">
            <i class="fas fa-cog me-2"></i> Account Settings
        </a>
        <a href="<?php echo url('guide/profile-settings'); ?>" class="list-group-item list-group-item-action rounded-3 m-2 <?php echo $isProfile ? 'active' : ''; ?>" style="<?php echo $isProfile ? 'background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); color: white; border: none;' : ''; ?>">
            <i class="fas fa-user me-2"></i> Profile Settings
        </a>
        <a href="<?php echo url('guide/password-settings'); ?>" class="list-group-item list-group-item-action rounded-3 m-2 <?php echo $isPassword ? 'active' : ''; ?>" style="<?php echo $isPassword ? 'background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); color: white; border: none;' : ''; ?>">
            <i class="fas fa-key me-2"></i> Change Password
        </a>
    </div>
    </div>