<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer {
    private $mailer;
    
    public function __construct() {
        $this->mailer = new PHPMailer(true);
        
        // Server settings
        $this->mailer->isSMTP();
        $this->mailer->Host = 'smtp.gmail.com';
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = 'phantritai1552004@gmail.com'; // Thay bằng email của bạn
        $this->mailer->Password = 'feia osgq emrk aepf'; // Thay bằng mật khẩu ứng dụng
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port = 587;
        
        // Default sender
        $this->mailer->setFrom('your-email@gmail.com', SITE_NAME);
    }
    
    public function sendLoginNotification($userEmail, $userName, $loginTime, $ipAddress) {
        try {
            $this->mailer->addAddress($userEmail, $userName);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Thông báo đăng nhập mới';
            
            // Email body
            $body = "
                <h2>Thông báo đăng nhập mới</h2>
                <p>Xin chào {$userName},</p>
                <p>Chúng tôi nhận thấy có một lần đăng nhập mới vào tài khoản của bạn:</p>
                <ul>
                    <li>Thời gian: {$loginTime}</li>
                    <li>Địa chỉ IP: {$ipAddress}</li>
                </ul>
                <p>Nếu đây là bạn, bạn có thể bỏ qua email này.</p>
                <p>Nếu đây không phải là bạn, vui lòng liên hệ với chúng tôi ngay lập tức.</p>
                <p>Trân trọng,<br>" . SITE_NAME . "</p>
            ";
            
            $this->mailer->Body = $body;
            $this->mailer->AltBody = strip_tags($body);
            
            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            error_log("Email sending failed: " . $e->getMessage());
            return false;
        }
    }
} 