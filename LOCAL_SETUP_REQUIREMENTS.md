# 🔧 RealLife RPG - Điều Kiện Chạy Local & Sử Dụng Toàn Bộ Tính Năng

> **Cập nhật**: 2026-06-24
> **Mục đích**: Liệt kê **TẤT CẢ** điều kiện cần và đủ để chạy dự án ở local với đầy đủ tính năng

---

## 📋 Checklist Tổng Quan

| # | Điều kiện | Bắt buộc? | Cho tính năng |
|---|-----------|-----------|---------------|
| 1 | PHP >= 8.2 | ✅ BẮT BUỘC | Backend core |
| 2 | Composer | ✅ BẮT BUỘC | PHP dependencies |
| 3 | MySQL/MariaDB | ✅ BẮT BUỘC | Database |
| 4 | Node.js >= 18.x | ✅ BẮT BUỘC | Mobile app + Vite |
| 5 | NPM | ✅ BẮT BUỘC | Node packages |
| 6 | Redis | ⚠️ Khuyên dùng | Queue, Cache (có thể dùng file/database thay) |
| 7 | Android Studio + SDK | ✅ BẮT BUỘC (Mobile) | Android Emulator |
| 8 | Xcode | ⚙️ macOS only | iOS Simulator |
| 9 | Gmail App Password | ⚠️ Khuyên dùng | Gửi email thật (verify, reset) |
| 10 | Gemini API Key | ⚙️ Tùy chọn | AI Chat feature |
| 11 | AWS S3 Bucket | ⚙️ Tùy chọn | Cloud file storage |
| 12 | Google/Facebook/GitHub OAuth | ⚙️ Tùy chọn | Social Login (chưa implement) |
| 13 | Expo Go App | ⚠️ Khuyên dùng | Test trên thiết bị thật |

---

## 🖥️ PHẦN 1: Backend Laravel

### 1.1 Yêu Cầu Phần Mềm

#### PHP >= 8.2
```bash
# Kiểm tra phiên bản
php -v

# Extensions cần thiết (Laravel 12 yêu cầu):
# - BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML
# - pdo_mysql (cho MySQL)
# - curl (cho HTTP requests - AI Chat, Socialite)
# - redis (nếu dùng Redis)

# Kiểm tra extensions
php -m
```

#### Composer (Latest)
```bash
# Kiểm tra
composer --version

# Cài đặt (Windows): https://getcomposer.org/download/
```

#### MySQL/MariaDB
```bash
# Kiểm tra
mysql --version

# Cần tạo database:
mysql -u root -p
CREATE DATABASE reallife_rpg CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

> **Khuyên dùng**: XAMPP / Laragon / Docker cho Windows (gồm PHP + MySQL + Apache)

#### Node.js >= 18.x & NPM
```bash
# Kiểm tra
node -v
npm -v

# Cài đặt: https://nodejs.org/
```

---

### 1.2 Cài Đặt Backend

```bash
# 1. Di chuyển vào thư mục backend
cd c:\Laravel\RealLife_RPG\RealLife_RPG

# 2. Cài PHP dependencies
composer install

# 3. Cài Node dependencies (cho laravel-echo, pusher-js)
npm install

# 4. Copy env (nếu chưa có)
copy .env.example .env

# 5. Generate app key
php artisan key:generate

# 6. Chạy migrations
php artisan migrate

# 7. Seed data mẫu
php artisan db:seed

# 8. Tạo storage symlink
php artisan storage:link
```

---

### 1.3 Cấu Hình `.env` Backend - CHI TIẾT

```env
#==============================================================================
# 🔑 CORE APP CONFIG
#==============================================================================
APP_NAME="Reallife RPG"
APP_ENV=local
APP_KEY=                                    # ← Tự generate bằng php artisan key:generate
APP_DEBUG=true
APP_URL=http://localhost:8000
FRONTEND_URL=http://localhost:3000          # URL frontend (nếu có)
MOBILE_URL=realliferpg://                   # Deep link scheme
APP_LOCALE=vi
APP_FALLBACK_LOCALE=en

#==============================================================================
# 🗄️ DATABASE (BẮT BUỘC)
#==============================================================================
DB_CONNECTION=mysql
DB_HOST=localhost                           # hoặc 127.0.0.1
DB_PORT=3306
DB_DATABASE=reallife_rpg                    # Tên database đã tạo
DB_USERNAME=root
DB_PASSWORD=your_password_here              # ← MẬT KHẨU MYSQL CỦA BẠN

#==============================================================================
# 📧 MAIL CONFIG
#==============================================================================
# Option A: Gửi email thật qua Gmail (cho verify email, reset password)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_gmail_app_password       # ← App Password từ Google Account
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"

# Option B: Chỉ ghi log (KHÔNG gửi email thật - phù hợp dev nhanh)
# MAIL_MAILER=log
# MAIL_FROM_ADDRESS=noreply@localhost
# MAIL_FROM_NAME="${APP_NAME}"

#==============================================================================
# 📡 BROADCASTING - REALTIME CHAT (cho WebSocket)
#==============================================================================
BROADCAST_CONNECTION=reverb                 # Dùng Laravel Reverb

# Reverb config (tạo tùy ý, phải match với mobile app)
REVERB_APP_ID=365621
REVERB_APP_KEY=ugh7jkxqznd3ajxeigp8
REVERB_APP_SECRET=9iedf9ml6jlfatexpa4x
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"

#==============================================================================
# ⚙️ QUEUE & CACHE
#==============================================================================
QUEUE_CONNECTION=database                   # Dùng database queue (cần jobs table)
# QUEUE_CONNECTION=sync                     # Hoặc sync nếu không muốn queue worker
CACHE_STORE=file                            # Hoặc redis nếu có Redis
CACHE_DRIVER=file
SESSION_DRIVER=file
FILESYSTEM_DISK=public

#==============================================================================
# 🔴 REDIS (TÙY CHỌN - nếu muốn dùng Redis cho cache/queue)
#==============================================================================
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
REDIS_PASSWORD=
REDIS_USERNAME=

#==============================================================================
# 🤖 AI CHAT (TÙY CHỌN - cho tính năng AI Chat)
#==============================================================================
GEMINI_API_KEY=your_gemini_api_key          # ← Lấy từ https://aistudio.google.com/apikey

#==============================================================================
# ☁️ AWS S3 (TÙY CHỌN - cho cloud storage)
#==============================================================================
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=ap-southeast-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

#==============================================================================
# 🔐 OAUTH (TÙY CHỌN - Social Login - CHƯA IMPLEMENT)
#==============================================================================
# GOOGLE_CLIENT_ID=
# GOOGLE_CLIENT_SECRET=
# GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
# FACEBOOK_CLIENT_ID=
# FACEBOOK_CLIENT_SECRET=
# FACEBOOK_REDIRECT_URI=http://localhost:8000/auth/facebook/callback
# GITHUB_CLIENT_ID=
# GITHUB_CLIENT_SECRET=
# GITHUB_REDIRECT_URI=http://localhost:8000/auth/github/callback
```

---

### 1.4 Chạy Backend

#### Cách 1: Chạy tất cả cùng lúc (Khuyến nghị)
```bash
# Cần cài concurrently trước:
npm install -g concurrently

# Chạy:
composer dev
```
Lệnh `composer dev` sẽ đồng thời chạy:
- `php artisan serve` → API Server: http://localhost:8000
- `php artisan queue:listen` → Queue Worker
- `npm run dev` → Vite (nếu có frontend views)

#### Cách 2: Chạy riêng từng service (mở nhiều terminal)
```bash
# Terminal 1: Laravel Server
php artisan serve

# Terminal 2: Queue Worker (cho jobs và notifications)
php artisan queue:work

# Terminal 3: Reverb WebSocket (cho realtime chat)
php artisan reverb:start

# Terminal 4: Schedule Worker (cho daily task generation)
php artisan schedule:work
```

---

## 📱 PHẦN 2: Mobile App (React Native Expo)

### 2.1 Yêu Cầu Phần Mềm

#### Node.js >= 18.x & NPM
```bash
node -v    # >= 18.x
npm -v
```

#### Android Studio (cho Android Emulator)
- Download: https://developer.android.com/studio
- Cần cài đặt:
  - Android SDK
  - Android SDK Platform-Tools
  - Android Emulator
  - Tạo AVD (Android Virtual Device)
- Thêm vào PATH: `%LOCALAPPDATA%\Android\Sdk\platform-tools`

#### Xcode (chỉ macOS - cho iOS Simulator)
- Cài từ Mac App Store
- Chạy: `xcode-select --install`

#### Expo Go App (cho thiết bị thật)
- Android: [Google Play Store](https://play.google.com/store/apps/details?id=host.exp.exponent)
- iOS: [App Store](https://apps.apple.com/app/expo-go/id982107779)

---

### 2.2 Cài Đặt Mobile App

```bash
# 1. Di chuyển vào thư mục mobile
cd c:\Laravel\RealLife_RPG\RealLife_RPG_Mobile

# 2. Cài dependencies
npm install

# 3. Copy/sửa .env
```

---

### 2.3 Cấu Hình `.env` Mobile - CHI TIẾT

```env
#==============================================================================
# 🔗 API URL (BẮT BUỘC - phải trỏ đúng backend)
#==============================================================================

# Cho Android Emulator (sử dụng IP đặc biệt 10.0.2.2):
EXPO_PUBLIC_API_URL=http://10.0.2.2:8000/api/v1

# Cho iOS Simulator:
# EXPO_PUBLIC_API_URL=http://localhost:8000/api/v1

# Cho thiết bị thật (dùng IP LAN của máy tính):
# EXPO_PUBLIC_API_URL=http://192.168.x.x:8000/api/v1
# (Chạy ipconfig để tìm IP LAN)

#==============================================================================
# 📡 REVERB WEBSOCKET (cho Realtime Chat)
#==============================================================================
EXPO_PUBLIC_REVERB_APP_KEY=ugh7jkxqznd3ajxeigp8    # ← Phải match backend
EXPO_PUBLIC_REVERB_HOST=localhost                     # ← Hoặc IP LAN
EXPO_PUBLIC_REVERB_PORT=8080
EXPO_PUBLIC_REVERB_SCHEME=http
```

> ⚠️ **QUAN TRỌNG**: Sau khi thay đổi `.env`, **PHẢI** restart Expo dev server với cache clear:
> ```bash
> npx expo start -c
> ```

---

### 2.4 Chạy Mobile App

```bash
# Start Expo Dev Server
npx expo start

# Hoặc
npm start
```

Sau đó:
- Nhấn **`a`** → Mở Android Emulator
- Nhấn **`i`** → Mở iOS Simulator (macOS only)
- Quét **QR Code** bằng Expo Go → Test trên thiết bị thật

#### Development Build (khuyến nghị cho đầy đủ tính năng):
```bash
# Android
npx expo run:android

# iOS (macOS only)
npx expo run:ios
```

---

## 🎯 PHẦN 3: Điều Kiện Cho Từng Tính Năng

### 🟢 Tính Năng Cơ Bản (Chạy ngay sau cài đặt)

| Tính năng | Backend cần | Mobile cần | Ghi chú |
|-----------|-------------|------------|---------|
| Đăng ký / Đăng nhập | PHP + MySQL + `php artisan serve` | Expo + .env đúng API_URL | ✅ Hoạt động ngay |
| CRUD Tasks | Backend chạy | Mobile chạy | ✅ Hoạt động ngay |
| Xem Profile & Stats | Backend chạy | Mobile chạy | ✅ Hoạt động ngay |
| Xem Achievements | Backend + `db:seed` | Mobile chạy | Cần seed data |
| Shop & Inventory | Backend + `db:seed` | Mobile chạy | Cần seed items |
| Bảng xếp hạng | Backend + nhiều users | Mobile chạy | Cần seed users |
| Focus Mode | Backend chạy | Mobile chạy | ✅ Hoạt động ngay |
| Onboarding | Backend chạy | Mobile chạy | ✅ Hoạt động ngay |

### 🟡 Tính Năng Cần Cấu Hình Thêm

| Tính năng | Điều kiện thêm | Cách setup |
|-----------|---------------|------------|
| **Xác thực Email** | SMTP Gmail hoạt động | Cần Gmail + App Password, `MAIL_MAILER=smtp` |
| **Reset Password** | SMTP Gmail hoạt động | Cùng với xác thực email |
| **Realtime Chat** | Reverb WebSocket server | Chạy `php artisan reverb:start` |
| **Queue Jobs** | Queue Worker | Chạy `php artisan queue:work`, đổi `QUEUE_CONNECTION=database` |
| **Daily Task Auto-gen** | Scheduler | Chạy `php artisan schedule:work` |
| **AI Chat** | Gemini API Key | Đăng ký tại https://aistudio.google.com, thêm `GEMINI_API_KEY` |
| **File Upload (Cloud)** | AWS S3 | Tạo S3 bucket, thêm AWS credentials |
| **Push Notifications** | Expo Push Service | Cần development build, không dùng Expo Go |

### 🔴 Tính Năng Chưa Implement

| Tính năng | Trạng thái | Ghi chú |
|-----------|------------|---------|
| **Social Login** (Google/Facebook/GitHub) | ❌ Chưa có code | Có dependencies nhưng chưa có Controller |
| **Guild System** | ⚠️ Có Models nhưng chưa có API | Guild, GuildMember, GuildJoinRequest models tồn tại |
| **Note System** | ⚠️ Có Model + Repository | Chưa có Controller/Routes |
| **Activity Comments & Reactions** | ⚠️ Có Models | Chưa có API endpoints |
| **Notification Preferences** | ⚠️ Có Model | Chưa có API endpoints |

---

## 🚀 PHẦN 4: Quick Start - Chạy Nhanh Nhất

### Bước 1: Backend (3 phút)
```bash
cd c:\Laravel\RealLife_RPG\RealLife_RPG

# Cài đặt
composer install
npm install
copy .env.example .env
php artisan key:generate

# Sửa .env: DB_PASSWORD=your_mysql_password

# Database
php artisan migrate
php artisan db:seed

# Chạy
php artisan serve
```

### Bước 2: Mobile (2 phút)
```bash
cd c:\Laravel\RealLife_RPG\RealLife_RPG_Mobile
npm install

# Sửa .env:
# EXPO_PUBLIC_API_URL=http://10.0.2.2:8000/api/v1

npx expo start
# Nhấn 'a' để mở Android Emulator
```

### Bước 3: Mở tất cả services (cho đầy đủ tính năng)
```bash
# Terminal 1: Backend API
cd c:\Laravel\RealLife_RPG\RealLife_RPG
php artisan serve

# Terminal 2: Queue Worker
php artisan queue:work

# Terminal 3: WebSocket (Realtime Chat)
php artisan reverb:start

# Terminal 4: Scheduler (Daily Tasks)
php artisan schedule:work

# Terminal 5: Mobile App
cd c:\Laravel\RealLife_RPG\RealLife_RPG_Mobile
npx expo start
```

---

## ⚙️ PHẦN 5: Services Cần Chạy Đồng Thời

```
┌─────────────────────────────────────────────────────────────┐
│                    LOCAL DEVELOPMENT                         │
│                                                             │
│  Terminal 1: php artisan serve          → :8000 (API)       │
│  Terminal 2: php artisan queue:work     → Queue Worker      │
│  Terminal 3: php artisan reverb:start   → :8080 (WebSocket) │
│  Terminal 4: php artisan schedule:work  → Cron Scheduler    │
│  Terminal 5: npx expo start             → :8081 (Metro)     │
│                                                             │
│  Hoặc dùng: composer dev (gộp Terminal 1+2, thiếu 3+4)     │
└─────────────────────────────────────────────────────────────┘
```

---

## 🔗 PHẦN 6: Các URL & Port

| Service | URL | Port |
|---------|-----|------|
| Laravel API | http://localhost:8000 | 8000 |
| Reverb WebSocket | ws://localhost:8080 | 8080 |
| Expo Metro Bundler | http://localhost:8081 | 8081 |
| MySQL | localhost:3306 | 3306 |
| Redis | localhost:6379 | 6379 |

---

## 🛠️ PHẦN 7: Troubleshooting Nhanh

| Vấn đề | Nguyên nhân | Giải pháp |
|---------|-------------|-----------|
| `SQLSTATE[HY000]` | MySQL chưa chạy | Khởi động MySQL service |
| `Class not found` | Chưa chạy `composer install` | `composer install` |
| `Module not found` (Mobile) | Chưa chạy `npm install` | `cd RealLife_RPG_Mobile && npm install` |
| Mobile không kết nối API | `.env` sai URL | Sửa `EXPO_PUBLIC_API_URL`, restart với `-c` |
| Email không gửi | `MAIL_MAILER=log` | Đổi sang `smtp` + config Gmail |
| Chat không realtime | Reverb chưa chạy | `php artisan reverb:start` |
| Daily tasks không tạo | Scheduler chưa chạy | `php artisan schedule:work` |
| AI Chat trả về lỗi | Thiếu `GEMINI_API_KEY` | Thêm key vào `.env` |
| `419 CSRF token mismatch` | Sanctum config | Kiểm tra stateful domains trong `config/sanctum.php` |
| Queue jobs không chạy | `QUEUE_CONNECTION=sync` | Đổi sang `database`, chạy `queue:work` |
