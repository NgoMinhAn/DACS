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

    public function sendPasswordReset($userEmail, $userName, $resetToken) {
        try {
            $this->mailer->clearAddresses(); // Clear any previous recipients
            $this->mailer->addAddress($userEmail, $userName);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = 'Đặt lại mật khẩu';
            
            // Create reset link
            $resetLink = URL_ROOT . '/account/reset-password/' . $resetToken;
            
            // Email body
            $body = "
                <h2>Đặt lại mật khẩu</h2>
                <p>Xin chào {$userName},</p>
                <p>Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.</p>
                <p>Vui lòng nhấp vào liên kết bên dưới để đặt lại mật khẩu của bạn:</p>
                <p><a href='{$resetLink}' style='display: inline-block; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Đặt lại mật khẩu</a></p>
                <p>Hoặc copy đường dẫn sau vào trình duyệt:</p>
                <p>{$resetLink}</p>
                <p><strong>Lưu ý quan trọng:</strong></p>
                <ul>
                    <li>Liên kết này sẽ hết hạn sau 24 giờ.</li>
                    <li>Vui lòng đặt lại mật khẩu ngay sau khi nhận được email này.</li>
                    <li>Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.</li>
                </ul>
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