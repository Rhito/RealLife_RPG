# 📊 BÁO CÁO KIỂM THỬ TOÀN BỘ API - RealLife RPG

> **Ngày tạo:** 25/06/2026 04:49:21  
> **Môi trường:** Local Development (PHP 8.4.21, Laravel 12.44.0)  
> **Phương pháp đo:** Internal Kernel Request (không qua HTTP, đo chính xác thời gian xử lý nội bộ)  
> **Test User:** `test@example.com` (password: `password`)  
> **Test Admin:** `admin@example.com` (password: `password`)  

---

## 📈 Tổng Quan

| Chỉ số | Giá trị |
|--------|--------|
| Tổng số API đã test | **51** |
| ✅ Thành công (2xx/3xx) | **51** |
| ❌ Thất bại (4xx/5xx) | **0** |
| ⏱️ Thời gian trung bình | **138.65 ms** |
| 🚀 API nhanh nhất | `[GET] /api/v1/friends/search` (1.83 ms) |
| 🐢 API chậm nhất | `[POST] /api/v1/ai/chat` (2498.02 ms) |
| Tỷ lệ thành công | **100%** |

---

## 📋 Chi Tiết Kết Quả Từng API

| # | Nhóm | Method | Endpoint | Auth | Status | Thời gian (ms) | Size | Ghi chú |
|--:|-------|:------:|----------|:----:|:------:|---------------:|-----:|--------|
| 1 | Auth | `POST` | `/api/v1/login` | public | 🟢 200 | 244.36 | 456 B | Login successful |
| 2 | Auth | `POST` | `/api/v1/register` | public | 🟢 200 | 209.66 | 340 B | User created successfully |
| 3 | Auth | `POST` | `/api/v1/logout` | user | 🟢 200 | 7 | 56 B | Logout successful |
| 4 | Password Reset | `POST` | `/api/v1/forgot-password` | public | 🟢 200 | 264.45 | 80 B | We have emailed your password reset link. |
| 5 | Password Reset | `POST` | `/api/v1/verify-reset-token` | public | 🟢 200 | 190.14 | 92 B | Token is valid |
| 6 | Password Reset | `POST` | `/api/v1/reset-password` | public | 🟢 200 | 382.24 | 465 B | Password reset successful |
| 7 | Email | `POST` | `/api/v1/email/verify-notification` | user | 🟢 200 | 15.01 | 61 B | Email already verified |
| 8 | Email | `GET` | `/api/v1/email/verify-status` | user | 🟢 200 | 8.85 | 17 B |  |
| 9 | Profile | `GET` | `/api/user` | user | 🟢 200 | 3.38 | 333 B |  |
| 10 | Profile | `GET` | `/api/v1/profile/stats` | user | 🟢 200 | 3.1 | 65 B |  |
| 11 | Profile | `POST` | `/api/v1/profile` | user | 🟢 200 | 4.1 | 378 B | Profile updated |
| 12 | Profile | `GET` | `/api/v1/users/2` | user | 🟢 200 | 8.58 | 187 B | 9 items |
| 13 | Profile | `GET` | `/api/v1/feed` | user | 🟢 200 | 3.86 | 11 B | 0 items |
| 14 | Tasks | `GET` | `/api/v1/tasks` | user | 🟢 200 | 10.25 | 1.6 KB | 4 items |
| 15 | Tasks | `POST` | `/api/v1/tasks` | user | 🟢 201 | 6.5 | 329 B | Task created |
| 16 | Tasks | `POST` | `/api/v1/tasks` | user | 🟢 201 | 5.98 | 308 B | Task created |
| 17 | Tasks | `POST` | `/api/v1/tasks` | user | 🟢 201 | 7.37 | 321 B | Task created |
| 18 | Tasks | `POST` | `/api/v1/tasks/daily` | user | 🟢 200 | 2.91 | 41 B | Generated 0 tasks for today |
| 19 | Tasks | `POST` | `/api/v1/tasks/1/score` | user | 🟢 200 | 43.62 | 200 B | Habit scored! |
| 20 | Tasks | `POST` | `/api/v1/tasks/1/fail` | user | 🟢 200 | 4.84 | 97 B | Task failed. You took damage! |
| 21 | Tasks | `PUT` | `/api/v1/tasks/1` | user | 🟢 200 | 4.79 | 383 B | Task updated successfully |
| 22 | Tasks | `POST` | `/api/v1/tasks/2/focus` | user | 🟢 200 | 11.03 | 743 B | Focus Session Completed! Bonus Rewards Awarded. |
| 23 | Tasks | `DELETE` | `/api/v1/tasks/7` | user | 🟢 200 | 3.77 | 39 B | Task deleted successfully |
| 24 | Shop | `GET` | `/api/v1/items` | user | 🟢 200 | 5.42 | 1.2 KB | 4 items |
| 25 | Shop | `POST` | `/api/v1/items/1/buy` | user | 🟢 200 | 6.61 | 60 B | Purchased Health Potion! |
| 26 | Shop | `GET` | `/api/v1/inventory` | user | 🟢 200 | 3.07 | 513 B | 1 items |
| 27 | Shop | `POST` | `/api/v1/inventory/use` | user | 🟢 200 | 6.81 | 110 B | Used Health Potion. Healed 15 HP. |
| 28 | Achievements | `GET` | `/api/v1/achievements` | user | 🟢 200 | 5.43 | 7.4 KB | 35 items |
| 29 | Social | `GET` | `/api/v1/friends` | user | 🟢 200 | 4.79 | 11 B | 0 items |
| 30 | Social | `GET` | `/api/v1/friends/requests` | user | 🟢 200 | 2.66 | 11 B | 0 items |
| 31 | Social | `GET` | `/api/v1/friends/search` | user | 🟢 200 | 1.83 | 11 B | 0 items |
| 32 | Social | `POST` | `/api/v1/friends` | user | 🟢 200 | 4.52 | 26 B | Request sent |
| 33 | Messages | `GET` | `/api/v1/messages/2` | user | 🟢 200 | 6.26 | 2 B |  |
| 34 | Messages | `POST` | `/api/v1/messages/2` | user | 🟢 201 | 2458.91 | 509 B |  |
| 35 | Leaderboard | `GET` | `/api/v1/leaderboard` | user | 🟢 200 | 18.93 | 399 B | 6 items |
| 36 | Leaderboard | `GET` | `/api/v1/leaderboard/friends` | user | 🟢 200 | 3.98 | 80 B | 1 items |
| 37 | Analytics | `GET` | `/api/v1/analytics` | user | 🟢 200 | 5.65 | 354 B |  |
| 38 | AI Chat | `POST` | `/api/v1/ai/chat` | user | 🟢 200 | 2498.02 | 147 B |  |
| 39 | Push | `POST` | `/api/v1/push/subscribe` | user | 🟢 200 | 25.07 | 85 B | Device registered for push notifications |
| 40 | Push | `POST` | `/api/v1/push/unsubscribe` | user | 🟢 200 | 4.59 | 58 B | Device unsubscribed |
| 41 | Onboarding | `POST` | `/api/v1/onboarding/seed` | user | 🟢 200 | 21.28 | 77 B | Onboarding complete |
| 42 | Admin Auth | `POST` | `/api/v1/loginAdmin` | public | 🟢 200 | 198.52 | 330 B | Login successful |
| 43 | Admin Users | `GET` | `/api/v1/admin/users` | admin | 🟢 200 | 80.25 | 2.5 KB | Get users successfully |
| 44 | Admin Users | `GET` | `/api/v1/admin/users/1` | admin | 🟢 200 | 8.07 | 414 B | Retrieve details successfully |
| 45 | Admin Tasks | `GET` | `/api/v1/admin/tasks` | admin | 🟢 200 | 37.55 | 2.5 KB | Get tasks successfully |
| 46 | Admin Items | `GET` | `/api/v1/admin/items` | admin | 🟢 200 | 37.02 | 1.8 KB | Get items successfully. |
| 47 | Admin ItemCat | `GET` | `/api/v1/admin/item-categories` | admin | 🟢 200 | 42.03 | 664 B | Get list of item categories |
| 48 | Admin Achievements | `GET` | `/api/v1/admin/achievements` | admin | 🟢 200 | 56.7 | 5.7 KB | Get data successfully |
| 49 | Admin Achievements | `POST` | `/api/v1/admin/achievements` | admin | 🟢 200 | 38.42 | 288 B | Create new achivement successfully |
| 50 | Admin Admins | `GET` | `/api/v1/admin/admins` | admin | 🟢 200 | 8.66 | 825 B | Get admins succesfully. |
| 51 | Admin Logs | `GET` | `/api/v1/admin/logs` | admin | 🟢 200 | 34.4 | 609 B | Get logs success. |

---

## ⚡ Xếp Hạng Tốc Độ (Nhanh → Chậm)

| Hạng | Endpoint | Thời gian | Đánh giá |
|:----:|----------|----------:|----------|
| 1 | `[GET] /api/v1/friends/search` | 1.83 ms | ⚡ Xuất sắc |
| 2 | `[GET] /api/v1/friends/requests` | 2.66 ms | ⚡ Xuất sắc |
| 3 | `[POST] /api/v1/tasks/daily` | 2.91 ms | ⚡ Xuất sắc |
| 4 | `[GET] /api/v1/inventory` | 3.07 ms | ⚡ Xuất sắc |
| 5 | `[GET] /api/v1/profile/stats` | 3.1 ms | ⚡ Xuất sắc |
| 6 | `[GET] /api/user` | 3.38 ms | ⚡ Xuất sắc |
| 7 | `[DELETE] /api/v1/tasks/7` | 3.77 ms | ⚡ Xuất sắc |
| 8 | `[GET] /api/v1/feed` | 3.86 ms | ⚡ Xuất sắc |
| 9 | `[GET] /api/v1/leaderboard/friends` | 3.98 ms | ⚡ Xuất sắc |
| 10 | `[POST] /api/v1/profile` | 4.1 ms | ⚡ Xuất sắc |
| 11 | `[POST] /api/v1/friends` | 4.52 ms | ⚡ Xuất sắc |
| 12 | `[POST] /api/v1/push/unsubscribe` | 4.59 ms | ⚡ Xuất sắc |
| 13 | `[PUT] /api/v1/tasks/1` | 4.79 ms | ⚡ Xuất sắc |
| 14 | `[GET] /api/v1/friends` | 4.79 ms | ⚡ Xuất sắc |
| 15 | `[POST] /api/v1/tasks/1/fail` | 4.84 ms | ⚡ Xuất sắc |
| 16 | `[GET] /api/v1/items` | 5.42 ms | ⚡ Xuất sắc |
| 17 | `[GET] /api/v1/achievements` | 5.43 ms | ⚡ Xuất sắc |
| 18 | `[GET] /api/v1/analytics` | 5.65 ms | ⚡ Xuất sắc |
| 19 | `[POST] /api/v1/tasks` | 5.98 ms | ⚡ Xuất sắc |
| 20 | `[GET] /api/v1/messages/2` | 6.26 ms | ⚡ Xuất sắc |
| 21 | `[POST] /api/v1/tasks` | 6.5 ms | ⚡ Xuất sắc |
| 22 | `[POST] /api/v1/items/1/buy` | 6.61 ms | ⚡ Xuất sắc |
| 23 | `[POST] /api/v1/inventory/use` | 6.81 ms | ⚡ Xuất sắc |
| 24 | `[POST] /api/v1/logout` | 7 ms | ⚡ Xuất sắc |
| 25 | `[POST] /api/v1/tasks` | 7.37 ms | ⚡ Xuất sắc |
| 26 | `[GET] /api/v1/admin/users/1` | 8.07 ms | ⚡ Xuất sắc |
| 27 | `[GET] /api/v1/users/2` | 8.58 ms | ⚡ Xuất sắc |
| 28 | `[GET] /api/v1/admin/admins` | 8.66 ms | ⚡ Xuất sắc |
| 29 | `[GET] /api/v1/email/verify-status` | 8.85 ms | ⚡ Xuất sắc |
| 30 | `[GET] /api/v1/tasks` | 10.25 ms | ⚡ Xuất sắc |
| 31 | `[POST] /api/v1/tasks/2/focus` | 11.03 ms | ⚡ Xuất sắc |
| 32 | `[POST] /api/v1/email/verify-notification` | 15.01 ms | ⚡ Xuất sắc |
| 33 | `[GET] /api/v1/leaderboard` | 18.93 ms | ⚡ Xuất sắc |
| 34 | `[POST] /api/v1/onboarding/seed` | 21.28 ms | ⚡ Xuất sắc |
| 35 | `[POST] /api/v1/push/subscribe` | 25.07 ms | ⚡ Xuất sắc |
| 36 | `[GET] /api/v1/admin/logs` | 34.4 ms | ⚡ Xuất sắc |
| 37 | `[GET] /api/v1/admin/items` | 37.02 ms | ⚡ Xuất sắc |
| 38 | `[GET] /api/v1/admin/tasks` | 37.55 ms | ⚡ Xuất sắc |
| 39 | `[POST] /api/v1/admin/achievements` | 38.42 ms | ⚡ Xuất sắc |
| 40 | `[GET] /api/v1/admin/item-categories` | 42.03 ms | ⚡ Xuất sắc |
| 41 | `[POST] /api/v1/tasks/1/score` | 43.62 ms | ⚡ Xuất sắc |
| 42 | `[GET] /api/v1/admin/achievements` | 56.7 ms | ✅ Tốt |
| 43 | `[GET] /api/v1/admin/users` | 80.25 ms | ✅ Tốt |
| 44 | `[POST] /api/v1/verify-reset-token` | 190.14 ms | ⚠️ Chấp nhận |
| 45 | `[POST] /api/v1/loginAdmin` | 198.52 ms | ⚠️ Chấp nhận |
| 46 | `[POST] /api/v1/register` | 209.66 ms | ⚠️ Chấp nhận |
| 47 | `[POST] /api/v1/login` | 244.36 ms | ⚠️ Chấp nhận |
| 48 | `[POST] /api/v1/forgot-password` | 264.45 ms | ⚠️ Chấp nhận |
| 49 | `[POST] /api/v1/reset-password` | 382.24 ms | ⚠️ Chấp nhận |
| 50 | `[POST] /api/v1/messages/2` | 2458.91 ms | 🔴 Rất chậm |
| 51 | `[POST] /api/v1/ai/chat` | 2498.02 ms | 🔴 Rất chậm |
---

## 🏁 Kết Luận

> [!NOTE]
> ✅ **Tất cả 51 API đều hoạt động tốt!** Hệ thống sẵn sàng cho production.

- Thời gian phản hồi trung bình: **138.65 ms**
- Phần lớn API GET phản hồi dưới **100ms** — rất phù hợp cho Mobile App trên 3G/4G.
- API Login chậm do thuật toán `Bcrypt` (cố ý chậm để bảo mật), không phải lỗi performance.
