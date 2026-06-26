# 🗺️ RealLife RPG - Project Structure Map

> **Cập nhật lần cuối**: 2026-06-24
> **Tổng quan**: Dự án gồm 2 phần - Backend Laravel 12 (API) và Mobile App React Native Expo

---

## 📁 Cấu Trúc Tổng Quan

```
RealLife_RPG/                              # 🏠 Root dự án
├── .easignore                             # EAS Build ignore rules
├── .gitignore                             # Git ignore rules
├── cacert.pem                             # SSL Certificate bundle
├── HUONG_DAN_CAI_DAT.txt                  # Hướng dẫn cài đặt
├── PROJECT_MAP.md                         # ← File này (bản đồ dự án)
├── ERROR_REPORT.md                        # Báo cáo lỗi dự án
├── LOCAL_SETUP_REQUIREMENTS.md            # Điều kiện chạy local
│
├── RealLife_RPG/                          # 🖥️ BACKEND - Laravel 12
│   └── (Chi tiết bên dưới)
│
└── RealLife_RPG_Mobile/                   # 📱 MOBILE APP - React Native Expo
    └── (Chi tiết bên dưới)
```

---

## 🖥️ Backend - Laravel 12 (RealLife_RPG/)

```
RealLife_RPG/
│
├── 📄 Configs & Root Files
│   ├── .editorconfig                      # Editor settings
│   ├── .env                               # ⚠️ Environment (KHÔNG commit)
│   ├── .env.example                       # Template env
│   ├── .env.reverb_config                 # Reverb config mẫu
│   ├── .env.testing                       # Testing env
│   ├── .gitattributes                     # Git attributes
│   ├── .gitignore                         # Git ignore
│   ├── .styleci.yml                       # StyleCI config
│   ├── artisan                            # Laravel CLI entry point
│   ├── composer.json                      # PHP dependencies
│   ├── composer.lock                      # PHP lock file
│   ├── package.json                       # Node dependencies (echo, pusher-js)
│   ├── package-lock.json                  # Node lock file
│   ├── phpunit.xml                        # PHPUnit config
│   ├── CHANGELOG.md                       # Changelog
│   ├── DEPLOYMENT_GUIDE.md                # Hướng dẫn deploy
│   ├── README.md                          # README
│   ├── Procfile                           # Heroku deploy config
│   ├── railway.json                       # Railway deploy config
│   ├── render.yaml                        # Render deploy config
│   ├── .rnd                               # Random data file
│   │
│   ├── 🧪 Test Files (root - nên dọn dẹp)
│   │   ├── test_messaging.php             # Test messaging
│   │   ├── test_output.txt                # Test output
│   │   ├── test_rrule.php                 # Test RRule
│   │   ├── test_rrule_formats.php         # Test RRule formats
│   │   ├── test_rrule_init.php            # Test RRule init
│   │   └── test_task_enum.php             # Test Task Enum
│   │
│
├── 📂 app/                                # ⭐ Application Core
│   │
│   ├── 📂 Console/
│   │   └── Commands/
│   │       └── TestTaskEnum.php           # Artisan command test
│   │
│   ├── 📂 Enums/                          # Enum Definitions
│   │   ├── AdminRole.php                  # super, moderator
│   │   ├── ItemType.php                   # Loại item
│   │   ├── TaskDifficulty.php             # Độ khó task
│   │   ├── TaskPriority.php               # Ưu tiên task
│   │   ├── TaskStatus.php                 # Trạng thái task
│   │   └── TaskType.php                   # Loại task (daily, habit, todo)
│   │
│   ├── 📂 Events/                         # Broadcasting Events
│   │   ├── AdminLogEvent.php              # Event log admin
│   │   ├── MessageSent.php                # ⭐ Event tin nhắn realtime
│   │   └── UserVerified.php               # Event xác thực email
│   │
│   ├── 📂 Http/
│   │   ├── 📂 Controllers/
│   │   │   ├── ApiController.php          # Base API Controller (extends Controller)
│   │   │   ├── BaseCrudController.php     # Base CRUD (Dashboard)
│   │   │   ├── Controller.php             # Laravel Base Controller
│   │   │   │
│   │   │   ├── 📂 Auth/                   # 🔐 Authentication
│   │   │   │   ├── AuthenticatedController.php     # Login/Register/Logout
│   │   │   │   ├── EmailVerificationController.php # Email verify
│   │   │   │   ├── PasswordResetController.php     # API password reset
│   │   │   │   └── PasswordResetWebController.php  # Web password reset form
│   │   │   │
│   │   │   ├── 📂 User/                   # 👤 User-facing API
│   │   │   │   ├── AchievementController.php       # Achievements
│   │   │   │   ├── AiChatController.php            # ⭐ AI Chat (Gemini)
│   │   │   │   ├── AnalyticsController.php         # Analytics/Stats
│   │   │   │   ├── FriendController.php            # Friends system
│   │   │   │   ├── ItemController.php              # Shop & Inventory
│   │   │   │   ├── LeaderboardController.php       # Bảng xếp hạng
│   │   │   │   ├── MessageController.php           # Chat messages
│   │   │   │   ├── OnboardingController.php        # Onboarding flow
│   │   │   │   ├── ProfileController.php           # User profile
│   │   │   │   ├── PushSubscriptionController.php  # Push notifications
│   │   │   │   └── TaskController.php              # ⭐ Tasks (CRUD, complete, etc.)
│   │   │   │
│   │   │   └── 📂 DashBoard/              # 🛡️ Admin Dashboard API
│   │   │       ├── 📂 Achievement/
│   │   │       │   └── AchievementController.php
│   │   │       ├── 📂 Admin/
│   │   │       │   └── AdminController.php, AuthController.php
│   │   │       ├── 📂 Item/
│   │   │       │   └── ItemController.php
│   │   │       ├── 📂 ItemCategory/
│   │   │       │   └── ItemCategoryController.php
│   │   │       ├── 📂 Log/
│   │   │       │   └── LogController.php
│   │   │       ├── 📂 Task/
│   │   │       │   └── TaskController.php
│   │   │       └── 📂 User/
│   │   │           └── UserController.php
│   │   │
│   │   ├── 📂 Middleware/
│   │   │   ├── AdminRoleMiddleware.php     # Middleware phân quyền admin
│   │   │   └── EnsureEmailIsVerifiedApi.php # Middleware xác thực email
│   │   │
│   │   ├── 📂 Requests/                   # Form Requests Validation
│   │   │   ├── ApiFormRequest.php          # Base API form request
│   │   │   ├── LoginRequest.php           # Login validation
│   │   │   ├── 📂 Achievement/
│   │   │   ├── 📂 Admin/
│   │   │   ├── 📂 Item/
│   │   │   ├── 📂 ItemCategory/
│   │   │   ├── 📂 Task/
│   │   │   │   ├── TaskCompletionRequest.php
│   │   │   │   ├── TaskRequest.php
│   │   │   │   ├── UpdateTaskCompletionRequest.php
│   │   │   │   └── UpdateTaskRequest.php
│   │   │   └── 📂 User/
│   │   │       ├── StoreUserAchievementRequest.php
│   │   │       ├── StoreUserItemRequest.php
│   │   │       ├── StoreUserRequest.php
│   │   │       ├── UpdateUserAchievementRequest.php
│   │   │       ├── UpdateUserItemRequest.php
│   │   │       └── UpdateUserRequest.php
│   │   │
│   │   └── 📂 Resources/
│   │       └── UserResource.php           # User API Resource
│   │
│   ├── 📂 Jobs/
│   │   └── DailyTaskGeneratorJob.php      # ⭐ Job tạo task hàng ngày
│   │
│   ├── 📂 Listeners/
│   │   └── AdminLogListener.php           # Listener ghi log admin
│   │
│   ├── 📂 Mail/
│   │   └── WelcomeEmail.php               # Welcome email template
│   │
│   ├── 📂 Models/                         # 📊 Database Models (29 files)
│   │   ├── Achievement.php                # Thành tựu
│   │   ├── ActivityComment.php            # Bình luận hoạt động
│   │   ├── ActivityFeed.php               # Feed hoạt động
│   │   ├── ActivityReaction.php           # Reactions
│   │   ├── Admin.php                      # Admin users
│   │   ├── AdminLog.php                   # Admin audit log
│   │   ├── Friendship.php                 # Quan hệ bạn bè
│   │   ├── Guild.php                      # Guild/Nhóm
│   │   ├── GuildJoinRequest.php           # Yêu cầu gia nhập guild
│   │   ├── GuildMember.php                # Thành viên guild
│   │   ├── HealthCheck.php                # Health check
│   │   ├── Item.php                       # Items (shop)
│   │   ├── ItemCategory.php               # Danh mục item
│   │   ├── ItemCategoryItem.php           # Pivot item-category
│   │   ├── Leaderboard.php                # Bảng xếp hạng
│   │   ├── Message.php                    # Tin nhắn
│   │   ├── Note.php                       # Ghi chú
│   │   ├── Notification.php               # Thông báo
│   │   ├── NotificationPreference.php     # Cài đặt thông báo
│   │   ├── PushSubscription.php           # Push subscription
│   │   ├── StatLog.php                    # Log thống kê
│   │   ├── Tag.php                        # Tags
│   │   ├── Task.php                       # ⭐ Nhiệm vụ
│   │   ├── TaskCompletion.php             # Hoàn thành task
│   │   ├── TaskInstance.php               # Instance task (recurring)
│   │   ├── TaskRecurrence.php             # Lịch lặp lại
│   │   ├── User.php                       # ⭐ Người dùng
│   │   ├── UserAchievement.php            # Pivot user-achievement
│   │   └── UserItem.php                   # Pivot user-item
│   │
│   ├── 📂 Notifications/
│   │   ├── CustomResetPasswordNotification.php  # Email reset password
│   │   └── CustomVerifyEmail.php                # Email xác thực
│   │
│   ├── 📂 Policies/                       # Authorization Policies (9 files)
│   │   ├── AchievementPolicy.php
│   │   ├── ItemCategoryPolicy.php
│   │   ├── ItemPolicy.php
│   │   ├── StatLogPolicy.php
│   │   ├── TaskCompletionPolicy.php
│   │   ├── TaskPolicy.php
│   │   ├── UserAchievementPolicy.php
│   │   ├── UserItemPolicy.php
│   │   └── UserPolicy.php
│   │
│   ├── 📂 Providers/
│   │   ├── AuthenticatedProvider.php      # Auth & Policy registration
│   │   ├── EventServiceProvider.php       # Event-Listener binding
│   │   └── RepositoryProvider.php         # Repository DI binding
│   │
│   ├── 📂 Repositories/                   # Repository Pattern
│   │   ├── BaseRepository.php             # Base repository
│   │   ├── 📂 Contracts/                  # Interfaces (12 files)
│   │   │   ├── RepositoryInterface.php
│   │   │   ├── AchievementRepositoryInterface.php
│   │   │   ├── AdminLogRepositoryInterface.php
│   │   │   ├── AdminRepositoryInterface.php
│   │   │   ├── ItemCategoryRepositoryInterface.php
│   │   │   ├── ItemRepositoryInterface.php
│   │   │   ├── StatLogRepositoryInterface.php
│   │   │   ├── TaskCompletionRepositoryInterface.php
│   │   │   ├── TaskRepositoryInterface.php
│   │   │   ├── UserAchievementRepositoryInterface.php
│   │   │   ├── UserItemRepositoryInterface.php
│   │   │   └── UserRepositoryInterface.php
│   │   ├── 📂 Achievement/
│   │   │   └── AchievementRepository.php
│   │   ├── 📂 Admin/
│   │   │   ├── AdminLogRepository.php
│   │   │   └── AdminRepository.php
│   │   ├── 📂 Item/
│   │   │   └── ItemRepository.php
│   │   ├── 📂 ItemCategory/
│   │   │   └── ItemCategoryRepository.php
│   │   ├── 📂 Note/
│   │   │   └── NoteRepository.php
│   │   ├── 📂 StatLog/
│   │   │   └── StatLogRepository.php
│   │   ├── 📂 Task/
│   │   │   └── TaskRepository.php
│   │   └── 📂 User/
│   │       ├── UserAchievementRepository.php
│   │       ├── UserItemRepository.php
│   │       └── UserRepository.php
│   │
│   ├── 📂 Services/                       # Business Logic
│   │   ├── BaseService.php                # Base service
│   │   ├── AchievementService.php         # Logic achievement
│   │   ├── GamificationService.php        # ⭐ XP, level, coins logic
│   │   ├── TaskGenerationService.php      # Tạo task tự động
│   │   ├── TaskService.php                # Logic task
│   │   ├── 📂 Dashboard/
│   │   │   └── 📂 Item/, ItemCategory/, Task/, User/
│   │   └── 📂 User/
│   │       └── UserStatService.php        # Thống kê user
│   │
│   └── 📂 Traits/
│       └── HttpResposeTrait.php           # ⚠️ Typo: "Respose" → "Response"
│
├── 📂 bootstrap/                          # Laravel bootstrap
│
├── 📂 config/                             # ⚙️ Configuration (14 files)
│   ├── app.php                            # App config
│   ├── auth.php                           # Auth guards & providers
│   ├── broadcasting.php                   # Reverb/Pusher config
│   ├── cache.php                          # Cache config
│   ├── cors.php                           # CORS settings
│   ├── database.php                       # DB connections
│   ├── filesystems.php                    # File storage (S3, local)
│   ├── logging.php                        # Log channels
│   ├── mail.php                           # Mail config
│   ├── queue.php                          # Queue config
│   ├── reverb.php                         # Reverb WebSocket
│   ├── sanctum.php                        # Sanctum API auth
│   ├── services.php                       # Third-party services
│   └── session.php                        # Session config
│
├── 📂 database/
│   ├── 📂 factories/                      # Model Factories (6 files)
│   │   ├── AchievementFactory.php
│   │   ├── AdminFactory.php
│   │   ├── ItemFactory.php
│   │   ├── TaskFactory.php
│   │   ├── TaskInstanceFactory.php
│   │   └── UserFactory.php
│   │
│   ├── 📂 migrations/                     # Database Migrations (20 files)
│   │   ├── 2025_01_01_000001_create_users_table.php
│   │   ├── 2025_01_01_000002_create_tasks_table.php
│   │   ├── 2025_01_01_000003_create_task_instances_table.php
│   │   ├── 2025_01_01_000004_create_items_table.php
│   │   ├── 2025_01_01_000005_create_social_features_table.php
│   │   ├── 2025_01_01_000006_create_friends_table.php
│   │   ├── 2025_01_01_000007_create_admins_table.php
│   │   ├── 2025_01_01_000008_add_visibility_to_activity_feeds.php
│   │   ├── 2025_01_01_000009_add_hp_to_users.php
│   │   ├── 2025_01_01_000010_update_achievements_table_structure.php
│   │   ├── 2025_01_01_000011_add_soft_deletes_to_achievements.php
│   │   ├── 2025_12_25_042941_add_google_id_to_users_table.php
│   │   ├── 2025_12_25_044359_add_social_ids_to_users_table.php
│   │   ├── 2025_12_29_075521_create_push_subscriptions_table.php
│   │   ├── 2025_12_29_092510_change_scheduled_date_to_datetime_...
│   │   ├── 2025_12_30_000000_create_task_recurrences_table.php
│   │   ├── 2026_01_03_040001_create_messages_table.php
│   │   ├── 2026_01_06_165909_create_jobs_table.php
│   │   ├── 2026_01_22_131835_add_is_pinned_to_tasks_table.php
│   │   └── 2026_02_28_061204_add_is_onboarded_to_users_table.php
│   │
│   └── 📂 seeders/                        # Database Seeders (6 files)
│       ├── DatabaseSeeder.php             # Main seeder
│       ├── AchievementSeeder.php          # Seed achievements
│       ├── AdminSeeder.php                # Seed admin accounts
│       ├── ItemSeeder.php                 # Seed shop items
│       ├── TaskSeeder.php                 # Seed sample tasks
│       └── UserSeeder.php                 # Seed test users
│
├── 📂 docs/                               # Documentation
│   ├── PASSWORD_RESET_NATIVE.md           # Password reset flow
│   └── TEST_ACCOUNTS.md                   # Tài khoản test
│
├── 📂 public/                             # Public assets
│   ├── .htaccess
│   ├── favicon.ico
│   ├── index.php                          # Entry point
│   ├── robots.txt
│   ├── build/                             # Vite build output
│   └── storage/                           # Symlink to storage/app/public
│
├── 📂 resources/
│   ├── 📂 js/                             # Frontend JS (Vite)
│   └── 📂 views/                          # Blade Templates
│       ├── Log.blade.php                  # Log viewer page
│       ├── verified.blade.php             # Email verified page
│       ├── verification-success.blade.php # Verify success
│       ├── verification-error.blade.php   # Verify error
│       ├── reset-password-redirect.blade.php
│       ├── 📂 auth/
│       │   ├── reset-password.blade.php   # Password reset form
│       │   └── reset-success.blade.php    # Reset success page
│       └── 📂 emails/                     # (Trống - cần tạo templates)
│
├── 📂 routes/                             # Route Definitions
│   ├── api.php                            # ⭐ API Routes (v1)
│   ├── channels.php                       # Broadcast Channels
│   ├── console.php                        # Console/Scheduler
│   └── web.php                            # Web Routes
│
├── 📂 storage/                            # Storage
│   ├── app/                               # App files
│   ├── framework/                         # Cache, sessions, views
│   └── logs/                              # Log files
│
├── 📂 tests/                              # Tests
│   ├── Pest.php                           # Pest config
│   ├── TestCase.php                       # Base test case
│   ├── Feature/                           # Feature tests
│   └── Unit/                              # Unit tests
│
├── 📂 vendor/                             # Composer dependencies
└── 📂 node_modules/                       # Node dependencies
```

---

## 📱 Mobile App - React Native Expo (RealLife_RPG_Mobile/)

```
RealLife_RPG_Mobile/
│
├── 📄 Root Config Files
│   ├── .env                               # ⚠️ Environment vars
│   ├── .env.example                       # Template (chỉ có docs, không có vars)
│   ├── .gitignore                         # Git ignore
│   ├── app.json                           # ⭐ Expo config (scheme, plugins)
│   ├── eas.json                           # EAS Build config
│   ├── eslint.config.js                   # ESLint config
│   ├── expo-env.d.ts                      # Expo env type defs
│   ├── metro.config.js                    # Metro bundler config
│   ├── package.json                       # Dependencies
│   ├── package-lock.json                  # Lock file
│   ├── tsconfig.json                      # TypeScript config
│   └── README.md                          # README
│
├── 📂 app/                                # ⭐ Expo Router Screens
│   ├── _layout.tsx                        # Root layout (AuthProvider, AlertProvider)
│   ├── index.tsx                          # Entry redirect
│   │
│   ├── 🔐 Auth Screens
│   │   ├── login.tsx                      # Đăng nhập
│   │   ├── register.tsx                   # Đăng ký
│   │   ├── forgot-password.tsx            # Quên mật khẩu
│   │   ├── reset-password.tsx             # Đặt lại mật khẩu
│   │   ├── verify-email.tsx               # Xác thực email
│   │   └── privacy-policy.tsx             # Chính sách quyền riêng tư
│   │
│   ├── 📚 Standalone Screens
│   │   ├── modal.tsx                      # Modal screen
│   │   ├── settings.tsx                   # Cài đặt
│   │   └── tutorial.tsx                   # Hướng dẫn sử dụng
│   │
│   ├── 📂 onboarding/                     # Onboarding Flow
│   │   └── index.tsx                      # Onboarding main screen
│   │
│   ├── 📂 users/                          # User Profile
│   │   └── [id].tsx                       # Dynamic user profile
│   │
│   ├── 📂 focus/                          # Focus Mode
│   │   └── [id].tsx                       # Focus timer for task
│   │
│   └── 📂 (tabs)/                         # ⭐ Tab Navigation
│       ├── _layout.tsx                    # Tab bar layout
│       ├── index.tsx                      # 🏠 Home/Dashboard tab
│       ├── achievements.tsx               # 🏆 Achievements tab
│       ├── adventure-log.tsx              # 📜 Adventure Log tab
│       ├── shop.tsx                       # 🛒 Shop tab
│       │
│       ├── 📂 tasks/                      # 📋 Tasks Tab
│       │   ├── _layout.tsx                # Tasks stack layout
│       │   ├── index.tsx                  # Tasks list (34KB - lớn!)
│       │   └── create.tsx                 # Create/Edit task
│       │
│       └── 📂 friends/                    # 👥 Friends Tab
│           ├── _layout.tsx                # Friends stack layout
│           ├── index.tsx                  # Friends list
│           └── 📂 chat/
│               └── [id].tsx               # Chat screen
│
├── 📂 components/                         # 🧩 Reusable Components
│   ├── AnalyticsChart.tsx                 # Biểu đồ analytics
│   ├── Avatar.tsx                         # User avatar
│   ├── Card.tsx                           # Card component
│   ├── CustomAlert.tsx                    # Alert modal
│   ├── NetworkStatus.tsx                  # Network status indicator
│   ├── StatBadge.tsx                      # Stat badge display
│   ├── TourOverlay.tsx                    # Tour overlay guide
│   ├── TourTarget.tsx                     # Tour target highlight
│   ├── external-link.tsx                  # External link handler
│   ├── haptic-tab.tsx                     # Haptic feedback tab
│   ├── hello-wave.tsx                     # Hello wave animation
│   ├── parallax-scroll-view.tsx           # Parallax scroll
│   ├── themed-text.tsx                    # Themed text
│   ├── themed-view.tsx                    # Themed view
│   └── 📂 ui/                            # UI Primitives
│       ├── collapsible.tsx                # Collapsible
│       ├── icon-symbol.tsx                # Icon symbol (Android)
│       └── icon-symbol.ios.tsx            # Icon symbol (iOS)
│
├── 📂 services/                           # 🔌 API Services (12 files)
│   ├── api.ts                             # ⭐ Axios instance & interceptors
│   ├── auth.ts                            # Auth service (login, register)
│   ├── tasks.ts                           # Tasks CRUD
│   ├── friends.ts                         # Friends API
│   ├── items.ts                           # Items/Shop API
│   ├── shop.ts                            # Shop service
│   ├── achievements.ts                    # Achievements API
│   ├── analytics.ts                       # Analytics API
│   ├── leaderboard.ts                     # Leaderboard API
│   ├── notifications.ts                   # Notifications service
│   ├── profile.ts                         # Profile API
│   └── MessageService.ts                  # Message/Chat API
│
├── 📂 context/                            # 🔄 React Context
│   ├── AuthContext.tsx                    # ⭐ Auth state management
│   ├── AlertContext.tsx                   # Global alert context
│   └── TourContext.tsx                    # Tour/Onboarding context
│
├── 📂 hooks/                              # 🪝 Custom Hooks
│   ├── use-color-scheme.ts                # Color scheme hook
│   ├── use-color-scheme.web.ts            # Web-specific color scheme
│   └── use-theme-color.ts                # Theme color hook
│
├── 📂 utils/                              # 🛠️ Utilities
│   ├── websocket.ts                       # ⭐ Reverb WebSocket client (14KB)
│   ├── responsive.ts                      # Responsive sizing utils
│   └── networkEventEmitter.ts             # Network event emitter
│
├── 📂 constants/                          # 📐 Constants
│   └── theme.ts                           # Theme colors & tokens
│
├── 📂 assets/                             # 🖼️ Assets
│   └── images/                            # App images & icons
│
├── 📂 scripts/                            # 📜 Helper Scripts
│   └── reset-project.js                   # Reset project script
│
├── 📂 android/                            # Android native project
├── 📂 node_modules/                       # Node dependencies
└── 📂 .expo/                              # Expo cache
```

---

## 🔗 API Routes Map (api.php - prefix /api/v1)

### Public Routes (throttle: 8 req/min)
| Method | URI | Controller | Mô tả |
|--------|-----|------------|-------|
| POST | `/login` | AuthenticatedController@login | Đăng nhập |
| POST | `/register` | AuthenticatedController@register | Đăng ký |
| POST | `/loginAdmin` | DashBoard\AuthController@login | Admin login |
| POST | `/forgot-password` | PasswordResetController@forgotPassword | Quên MK |
| POST | `/reset-password` | PasswordResetController@resetPassword | Đặt lại MK |
| POST | `/verify-reset-token` | PasswordResetController@verifyResetToken | Xác thực token |
| GET | `/email/verify/{id}/{hash}` | EmailVerificationController | Xác thực email |

### Authenticated Routes (throttle: 20 req/min)
| Method | URI | Controller | Mô tả |
|--------|-----|------------|-------|
| POST | `/email/verify-notification` | EmailVerificationController | Gửi email xác thực |
| GET | `/email/verify-status` | EmailVerificationController | Check trạng thái |

### User Routes (auth + verified)
| Method | URI | Controller | Mô tả |
|--------|-----|------------|-------|
| GET/POST | `/tasks` | TaskController | CRUD tasks |
| POST | `/tasks/daily` | TaskController@generateDaily | Tạo daily tasks |
| POST | `/tasks/{id}/complete` | TaskController@complete | Hoàn thành task |
| POST | `/tasks/{id}/score` | TaskController@scoreHabit | Chấm điểm habit |
| POST | `/tasks/{id}/fail` | TaskController@fail | Fail task |
| POST | `/tasks/{id}/focus` | TaskController@completeFocus | Focus mode |
| PUT | `/tasks/{id}` | TaskController@update | Cập nhật task |
| DELETE | `/tasks/{id}` | TaskController@destroy | Xóa task |
| DELETE | `/task-instances/{id}` | TaskController@destroyInstance | Xóa instance |
| POST | `/onboarding/seed` | OnboardingController@seed | Seed onboarding |
| GET | `/items` | ItemController@index | Danh sách items |
| POST | `/items/{id}/buy` | ItemController@buy | Mua item |
| GET | `/inventory` | ItemController@inventory | Kho đồ |
| POST | `/inventory/use` | ItemController@use | Sử dụng item |
| GET | `/achievements` | AchievementController@index | Thành tựu |
| POST | `/profile` | ProfileController@update | Cập nhật profile |
| GET | `/profile/stats` | ProfileController@stats | Thống kê |
| GET | `/feed` | ProfileController@feed | Activity feed |
| GET | `/users/{id}` | ProfileController@show | Xem user profile |
| GET/POST/PUT/DELETE | `/friends` | FriendController | Friends CRUD |
| GET | `/friends/requests` | FriendController@requests | Lời mời kết bạn |
| GET | `/friends/search` | FriendController@search | Tìm bạn |
| GET/POST | `/messages/{id}` | MessageController | Chat messages |
| POST | `/ai/chat` | AiChatController@chat | AI Chat |
| GET | `/logout` | AuthenticatedController@logout | Đăng xuất |
| GET | `/leaderboard` | LeaderboardController@index | Bảng xếp hạng |
| GET | `/leaderboard/friends` | LeaderboardController@friends | BXH bạn bè |
| GET | `/analytics` | AnalyticsController@index | Analytics |
| POST | `/push/subscribe` | PushSubscriptionController | Đăng ký push |
| POST | `/push/unsubscribe` | PushSubscriptionController | Hủy push |

### Admin Routes (prefix: /admin, roles: super/moderator)
| Method | URI | Controller | Mô tả |
|--------|-----|------------|-------|
| * | `/admin/users/*` | UserController | CRUD users (rpgResource) |
| * | `/admin/tasks/*` | TaskController | CRUD tasks (rpgResource) |
| * | `/admin/items/*` | ItemController | CRUD items (rpgResource) |
| * | `/admin/item-categories/*` | ItemCategoryController | CRUD categories |
| * | `/admin/achievements/*` | AchievementController | CRUD achievements |
| * | `/admin/admins/*` | AdminController | Quản lý admins (super only) |
| GET | `/admin/logs` | LogController | Xem logs (super only) |

---

## 📊 Database Models Relationship Map

```
User (users)
├── has many → Task (tasks)
├── has many → TaskInstance (task_instances)
├── has many → TaskCompletion (task_completions)
├── belongs to many → Item (items) via user_items
├── belongs to many → Achievement (achievements) via user_achievements
├── has many → StatLog (stat_logs)
├── has many → ActivityFeed (activity_feeds)
├── has many → ActivityComment (activity_comments)
├── has many → ActivityReaction (activity_reactions)
├── has many → Notification (notifications)
├── has many → NotificationPreference (notification_preferences)
├── has many → PushSubscription (push_subscriptions)
├── has many → Friendship (friendships)
├── has many → GuildMember (guild_members)
├── has many → Guild (guilds) [owner]
├── has many → GuildJoinRequest (guild_join_requests)
└── has many → Message (messages) [sender/receiver]

Task (tasks)
├── belongs to → User
├── has many → TaskInstance
├── has many → TaskCompletion
└── has one → TaskRecurrence

Item (items)
├── belongs to many → User via user_items
└── belongs to many → ItemCategory via item_category_items
```
