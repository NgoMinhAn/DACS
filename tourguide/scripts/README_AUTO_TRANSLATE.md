# Auto Translation Script - Hướng dẫn sử dụng

Script tự động dịch **TOÀN BỘ** các key translation còn thiếu từ tiếng Anh sang tiếng Việt sử dụng Gemini API.

## 📋 Mục lục

1. [Cấu hình ban đầu](#cấu-hình-ban-đầu)
2. [Cách sử dụng](#cách-sử dụng)
3. [Kết quả](#kết-quả)
4. [Troubleshooting](#troubleshooting)

---

## 🔧 Cấu hình ban đầu

### Bước 1: Lấy Gemini API Key

1. Truy cập: https://aistudio.google.com/apikey
2. Đăng nhập bằng tài khoản Google
3. Tạo API key mới hoặc sử dụng key có sẵn
4. Copy API key

### Bước 2: Cấu hình API Key

Mở file `app/config/config.php` và thêm API key:

```php
// Gemini API Key (set your Gemini API key here)
define('GEMINI_API_KEY', 'AIzaSyAFiBUGzlv3xIOFN6pqpTsB42y7nTlGdYs');
```

**Lưu ý:** Thay `AIzaSyAFiBUGzlv3xIOFN6pqpTsB42y7nTlGdYs` bằng API key của bạn.

---

## 🚀 Cách sử dụng

### Cách 1: Xem trước (Dry Run)

Chạy lệnh này để xem có bao nhiêu keys cần dịch mà **KHÔNG lưu** vào file:

```bash
php scripts/auto_translate.php --dry-run
```

**Kết quả:**
```
=== Auto Translation Script ===
Translating all missing keys from English to Vietnamese

DRY RUN MODE - No changes will be saved

Found 7 missing translation keys.
Starting translation of 7 keys...
[DRY RUN] Would translate 7 keys.

=== Summary ===
Total English keys: 247
Total Vietnamese keys: 245
Missing keys: 7
Translated: 7
Errors: 0

Run without --dry-run to apply translations.
```

### Cách 2: Dịch thực tế

Chạy lệnh này để **dịch và lưu** tất cả các key còn thiếu:

```bash
php scripts/auto_translate.php
```

**Kết quả:**
```
=== Auto Translation Script ===
Translating all missing keys from English to Vietnamese

Found 7 missing translation keys.
Starting translation of 7 keys...
✓ Successfully saved 7 translations to app/lang/vi.php

=== Summary ===
Total English keys: 247
Total Vietnamese keys: 252
Missing keys: 7
Translated: 7
Errors: 0

✓ Translation completed successfully!
```

---

## 📊 Kết quả

### Script sẽ:

1. ✅ Đọc file `app/lang/en.php` (file tiếng Anh - nguồn)
2. ✅ Đọc file `app/lang/vi.php` (file tiếng Việt - đích)
3. ✅ So sánh và tìm các key còn thiếu
4. ✅ Dịch tất cả các key còn thiếu bằng Gemini API
5. ✅ Lưu vào file `app/lang/vi.php`

### Thống kê:

- **Total English keys**: Tổng số keys trong file `en.php`
- **Total Vietnamese keys**: Tổng số keys trong file `vi.php` (trước khi dịch)
- **Missing keys**: Số keys còn thiếu cần dịch
- **Translated**: Số keys đã dịch thành công
- **Errors**: Số keys bị lỗi (nếu có)

---

## 🔍 Troubleshooting

### Lỗi: "GEMINI_API_KEY not found"

**Nguyên nhân:** API key chưa được cấu hình.

**Giải pháp:**
1. Kiểm tra file `app/config/config.php`
2. Đảm bảo dòng `define('GEMINI_API_KEY', '...')` có giá trị
3. Không được để trống: `define('GEMINI_API_KEY', '');`

### Lỗi: "Translation failed" hoặc "Errors: X"

**Nguyên nhân có thể:**
- API key không hợp lệ hoặc hết quota
- Mạng không ổn định
- Key có format đặc biệt không thể dịch

**Giải pháp:**
1. Kiểm tra API key trên https://aistudio.google.com/apikey
2. Kiểm tra quota còn lại
3. Chạy lại script (các key đã dịch sẽ không dịch lại)

### Lỗi: "cURL error 77" (SSL Certificate)

**Nguyên nhân:** Vấn đề SSL certificate trên Laragon/Windows.

**Giải pháp:** 
- Đã được xử lý tự động trong code
- Nếu vẫn lỗi, kiểm tra kết nối internet

### File không được cập nhật

**Nguyên nhân:** Quyền ghi file.

**Giải pháp:**
1. Kiểm tra quyền ghi file cho `app/lang/vi.php`
2. Đảm bảo thư mục `app/lang/` có quyền ghi

---

## 💡 Tips

1. **Chạy Dry Run trước:** Luôn chạy `--dry-run` trước để xem có bao nhiêu keys cần dịch
2. **Kiểm tra kết quả:** Sau khi dịch, mở file `app/lang/vi.php` để kiểm tra
3. **Chạy lại khi cần:** Khi thêm key mới vào `en.php`, chạy lại script để dịch
4. **Backup trước khi dịch:** Nên backup file `vi.php` trước khi chạy lần đầu

---

## 📝 Ví dụ sử dụng

### Scenario 1: Lần đầu sử dụng

```bash
# Bước 1: Xem trước
php scripts/auto_translate.php --dry-run

# Bước 2: Backup file vi.php (tùy chọn)
cp app/lang/vi.php app/lang/vi.php.backup

# Bước 3: Dịch thực tế
php scripts/auto_translate.php
```

### Scenario 2: Thêm key mới vào en.php

Sau khi thêm key mới vào `en.php`:

```bash
# Chạy script để dịch key mới
php scripts/auto_translate.php
```

### Scenario 3: Kiểm tra keys còn thiếu

```bash
# Chỉ xem, không dịch
php scripts/auto_translate.php --dry-run
```

---

## ⚠️ Lưu ý quan trọng

1. **SSL Verification:** Script đã tắt SSL verification cho development (Laragon/Windows). Trong production, nên cấu hình SSL đúng cách.

2. **Rate Limiting:** Script tự động delay 0.3 giây giữa các lần dịch để tránh rate limiting.

3. **Chỉ dịch string:** Script chỉ dịch các key có giá trị là **string** (không dịch array hoặc giá trị rỗng).

4. **Nested keys:** Script hỗ trợ nested keys (ví dụ: `home.title.subtitle`).

5. **Không ghi đè:** Script chỉ dịch các key **còn thiếu**, không ghi đè các key đã có.

---

## 📞 Hỗ trợ

Nếu gặp vấn đề, kiểm tra:
1. API key có hợp lệ không
2. Quota còn lại
3. Kết nối internet
4. Quyền ghi file

---

**Chúc bạn sử dụng thành công! 🎉**
