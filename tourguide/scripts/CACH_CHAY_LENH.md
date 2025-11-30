# 🖥️ CÁCH CHẠY LỆNH - Auto Translate

## 📍 Chạy ở đâu?

Lệnh `php scripts/auto_translate.php` được chạy từ **Terminal/Command Line** (không phải trên website).

---

## 🎯 CÁCH 1: Sử dụng Terminal trong VS Code / Cursor

### Bước 1: Mở Terminal
- Nhấn `Ctrl + `` (dấu backtick) hoặc
- Menu: `Terminal` → `New Terminal`

### Bước 2: Đảm bảo đang ở thư mục đúng
Terminal sẽ tự động mở ở thư mục project. Kiểm tra bằng lệnh:
```bash
pwd
```
Hoặc trên Windows:
```bash
cd
```

Nếu thấy đường dẫn: `D:\laragon\www\DACS\tourguide` → ✅ Đúng rồi!

### Bước 3: Chạy lệnh
```bash
php scripts/auto_translate.php
```

---

## 🎯 CÁCH 2: Sử dụng Command Prompt (Windows)

### Bước 1: Mở Command Prompt
- Nhấn `Win + R`
- Gõ: `cmd`
- Nhấn Enter

### Bước 2: Di chuyển đến thư mục project
```bash
cd D:\laragon\www\DACS\tourguide
```

### Bước 3: Chạy lệnh
```bash
php scripts/auto_translate.php
```

---

## 🎯 CÁCH 3: Sử dụng PowerShell (Windows)

### Bước 1: Mở PowerShell
- Nhấn `Win + X`
- Chọn `Windows PowerShell` hoặc `Terminal`

### Bước 2: Di chuyển đến thư mục project
```powershell
cd D:\laragon\www\DACS\tourguide
```

### Bước 3: Chạy lệnh
```powershell
php scripts/auto_translate.php
```

---

## 🎯 CÁCH 4: Sử dụng Laragon Terminal

### Bước 1: Mở Laragon
- Mở ứng dụng Laragon

### Bước 2: Click chuột phải vào project
- Tìm project `tourguide` trong danh sách
- Click chuột phải → `Open Terminal Here`

### Bước 3: Chạy lệnh
```bash
php scripts/auto_translate.php
```

---

## 📸 HÌNH ẢNH MINH HỌA

### Terminal trong VS Code/Cursor:
```
D:\laragon\www\DACS\tourguide> php scripts/auto_translate.php
```

### Command Prompt:
```
C:\Users\YourName> cd D:\laragon\www\DACS\tourguide
D:\laragon\www\DACS\tourguide> php scripts/auto_translate.php
```

---

## ✅ KIỂM TRA ĐÃ ĐÚNG CHƯA?

### Kiểm tra 1: Thư mục hiện tại
```bash
# Windows
cd

# Linux/Mac
pwd
```

Kết quả phải là: `D:\laragon\www\DACS\tourguide`

### Kiểm tra 2: File có tồn tại không?
```bash
# Windows
dir scripts\auto_translate.php

# Linux/Mac
ls scripts/auto_translate.php
```

Nếu thấy file → ✅ Đúng rồi!

### Kiểm tra 3: PHP có hoạt động không?
```bash
php -v
```

Nếu thấy version PHP → ✅ PHP đã cài đặt!

---

## 🚨 LỖI THƯỜNG GẶP

### Lỗi: "php is not recognized"

**Nguyên nhân:** PHP chưa được thêm vào PATH

**Giải pháp:**
1. Tìm đường dẫn PHP trong Laragon (thường là: `C:\laragon\bin\php\php-8.x.x\`)
2. Thêm vào PATH hoặc dùng full path:
```bash
C:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe scripts/auto_translate.php
```

### Lỗi: "The system cannot find the path specified"

**Nguyên nhân:** Đang ở sai thư mục

**Giải pháp:**
```bash
cd D:\laragon\www\DACS\tourguide
```

### Lỗi: "Could not open input file"

**Nguyên nhân:** Đang ở sai thư mục hoặc file không tồn tại

**Giải pháp:**
```bash
# Kiểm tra thư mục hiện tại
cd

# Di chuyển đến đúng thư mục
cd D:\laragon\www\DACS\tourguide

# Kiểm tra file có tồn tại
dir scripts\auto_translate.php
```

---

## 💡 TIPS

1. **Sử dụng Terminal trong VS Code/Cursor** - Dễ nhất, tự động ở đúng thư mục
2. **Tạo shortcut** - Có thể tạo file `.bat` để chạy nhanh:
   ```batch
   @echo off
   cd D:\laragon\www\DACS\tourguide
   php scripts/auto_translate.php
   pause
   ```
3. **Sử dụng Laragon Terminal** - Nếu dùng Laragon, cách này tiện nhất

---

## 🎯 TÓM TẮT

| Cách | Cách mở | Lệnh |
|------|---------|------|
| **VS Code/Cursor Terminal** | `Ctrl + `` | `php scripts/auto_translate.php` |
| **Command Prompt** | `Win + R` → `cmd` | `cd D:\laragon\www\DACS\tourguide`<br>`php scripts/auto_translate.php` |
| **PowerShell** | `Win + X` → Terminal | `cd D:\laragon\www\DACS\tourguide`<br>`php scripts/auto_translate.php` |
| **Laragon Terminal** | Click phải project → Terminal | `php scripts/auto_translate.php` |

---

**Chọn cách nào bạn thấy tiện nhất! 🚀**


