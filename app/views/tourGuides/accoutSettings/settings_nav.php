<div class="list-group mb-4">
    <a href="<?php echo url('guide/account-settings'); ?>" class="list-group-item list-group-item-action<?php if (strpos($_SERVER['REQUEST_URI'], 'account-settings') !== false && strpos($_SERVER['REQUEST_URI'], 'profile') === false && strpos($_SERVER['REQUEST_URI'], 'password') === false) echo ' active'; ?>">
        Account Settings
    </a>
    <a href="<?php echo url('guide/profile-settings'); ?>" class="list-group-item list-group-item-action<?php if (strpos($_SERVER['REQUEST_URI'], 'profile') !== false) echo ' active'; ?>">
        Profile Settings
    </a>
    <a href="<?php echo url('guide/password-settings'); ?>" class="list-group-item list-group-item-action<?php if (strpos($_SERVER['REQUEST_URI'], 'password') !== false) echo ' active'; ?>">
        Change Password
    </a>
</div>