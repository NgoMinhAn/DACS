# DACS
Đồ án cơ sở công nghệ thông tin - Website Kết Nối Trực Tiếp Khách Du Lịch với Hướng Dẫn Viên Tự Do (FreelanceTourGuide)

# Tour Guide Management System

## Cài đặt Project

1. Clone project từ GitHub:
```bash
git clone [repository-url]
cd DACS
```

2. Cài đặt dependencies:
```bash
composer install
```

3. Cấu hình môi trường:
- Copy file `.env.example` thành `.env`
- Cập nhật các thông tin cấu hình trong file `.env` (database, mail, etc.)

4. Tạo key cho ứng dụng:
```bash
php artisan key:generate
```

5. Chạy migration để tạo database:
```bash
php artisan migrate
```

6. Cài đặt và build assets:
```bash
npm install
npm run dev
```

## Cập nhật Project

Khi pull code mới từ GitHub, thực hiện các bước sau:

1. Lưu lại các thay đổi local (nếu có):
```bash
git stash
```

2. Pull code mới từ GitHub:
```bash
git pull origin main
```

3. Cập nhật dependencies:
```bash
composer install
```

4. Chạy migration nếu có thay đổi database:
```bash
php artisan migrate
```

5. Clear cache và config:
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

6. Cập nhật assets nếu có thay đổi:
```bash
npm install
npm run dev
```

7. Khôi phục các thay đổi local (nếu đã stash):
```bash
git stash pop
```

8. Kiểm tra file `.env`:
- Đảm bảo các thông tin cấu hình trong file `.env` vẫn chính xác
- Nếu có thay đổi về cấu hình, cập nhật lại file `.env`

9. Kiểm tra database:
- Đảm bảo thông tin kết nối database trong `.env` chính xác
- Kiểm tra xem có cần chạy thêm migration không

10. Khởi động lại server:
```bash
php artisan serve
```

## Yêu cầu hệ thống

- PHP >= 7.4
- Composer
- Node.js & NPM
- MySQL/MariaDB
- Web server (Apache/Nginx)

## Cấu trúc Project

- `app/` - Chứa code PHP chính của ứng dụng
- `config/` - Các file cấu hình
- `database/` - Migrations và seeds
- `public/` - File public và entry point
- `resources/` - Views, assets chưa biên dịch
- `routes/` - Định nghĩa routes
- `storage/` - File logs, cache, etc.
- `tests/` - Unit tests và feature tests

## Xử lý lỗi thường gặp

### 1. Lỗi thiếu extension zip
Nếu gặp lỗi "The zip extension and unzip/7z commands are both missing":
1. Mở file `php.ini` trong thư mục `C:\laragon\bin\php\php-8.3.16-Win32-vs16-x64\`
2. Tìm và bỏ comment (xóa dấu `;`) ở dòng: `extension=zip`
3. Lưu file và restart lại Laragon

### 2. Lỗi không tìm thấy vendor/autoload.php
Nếu gặp lỗi "Failed to open stream: No such file or directory in .../vendor/autoload.php":
1. Kiểm tra đã cài đặt Composer chưa bằng lệnh: `composer --version`
2. Nếu chưa cài đặt, tải và cài đặt Composer từ: https://getcomposer.org/download/
3. Sau đó chạy các lệnh:
```bash
Remove-Item -Recurse -Force vendor
Remove-Item -Force composer.lock
composer install
composer dump-autoload
```

## Liên hệ

Nếu có bất kỳ vấn đề gì, vui lòng liên hệ với team phát triển.
