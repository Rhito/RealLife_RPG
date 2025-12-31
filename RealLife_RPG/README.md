Chào bạn, với tư cách là một Senior Architect/Developer chuyên về Laravel và React Native, tôi đã phân tích kỹ file rlrpgV1.sql (bản cập nhật mới nhất với Notes, Tags, Task Instances) và cấu trúc dự án bạn đang hướng tới.
Dưới đây là Mô hình Tổng quát (Application Blueprint) cho ứng dụng RealLife RPG của bạn. Tài liệu này đóng vai trò là "bản vẽ kỹ thuật" để team dev (hoặc chính bạn) triển khai code một cách nhất quán.
________________________________________
1. Kiến Trúc Hệ Thống (High-Level Architecture)
Mô hình triển khai theo hướng Event-Driven Architecture để đảm bảo tính Real-time và Scalability.
•	Frontend (Client): React Native (App Router). Gọi API qua Axios/Fetch.
•	Backend (Core): Laravel 12 (API Service). Chịu trách nhiệm xử lý logic nghiệp vụ, Auth, Validation.
•	Database: MySQL 8.0 (Lưu trữ chính).
•	//add on - AI Service: External API (OpenAI/Gemini) hoặc Python Microservice (để xử lý ảnh proof).
________________________________________
2. Luồng Logic & Nghiệp vụ Chi tiết (Core Business Logic)
A. Hệ thống Nhiệm vụ (Task Engine)
Đây là trái tim của ứng dụng.
1.	Task Definition (Khuôn mẫu): User tạo tasks (ví dụ: "Tập Gym"). Cài đặt lặp lại trong task_recurrences (ví dụ: Thứ 2, 4, 6).
2.	Daily Generation (Sinh task hàng ngày):
o	Cronjob (00:00 mỗi ngày): Chạy DailyTaskGeneratorJob.
o	Quét tất cả tasks có lịch lặp vào hôm nay.
o	Tạo bản ghi mới trong task_instances cho ngày hiện tại.
o	Sao chép checklist_items từ template sang instance.
3.	Execution (Thực hiện):
o	User upload ảnh bằng chứng (nếu cần) -> Update task_instances status = completed.
o	Trigger Event: TaskCompleted.
B. Logic Gamification (RPG Engine)
Tính toán điểm thưởng dựa trên độ khó và chuỗi (streak).
Công thức tính EXP (Kinh nghiệm):
$$EXP_{reward} = BaseEXP \times DifficultyMultiplier \times (1 + StreakBonus)$$
•	BaseEXP: 10 (Mặc định)
•	Difficulty: Easy (x1), Medium (x1.5), Hard (x2)
•	StreakBonus: Mỗi ngày liên tiếp +10%, tối đa 50% (x1.5).
Logic Lên cấp (Level Up):
Sử dụng công thức đường cong lũy thừa (Exponential Curve) để càng lên cao càng khó.
$$EXP_{required} = 100 \times (CurrentLevel)^{1.5}$$
•	Ví dụ: Level 1 cần 100 EXP. Level 2 cần ~282 EXP.
•	Khi EXP hiện tại >= EXP Required:
1.	Tăng level +1.
2.	Reset EXP dư (hoặc trừ đi EXP required).
3.	Hồi full máu/năng lượng (nếu có tính năng này).
4.	Bắn Notification & Reverb Event: "Level Up!".
Công thức tính Vàng (Currency):
$$Gold = (BaseGold \times Difficulty) + Random(1, 5)$$
•	Yếu tố Random giúp tạo cảm giác "may mắn" (Reward variability) gây nghiện cho người dùng.
C. AI Proof Verification (Cơ chế kiểm duyệt AI)
Xử lý bất đồng bộ (Async) để không làm user phải chờ.
1.	Upload: User submit task kèm ảnh -> Ảnh lưu vào Storage (S3/Local).
2.	 API trả về "Đang xử lý"
3.	Processing: Job gửi ảnh sang AI (OpenAI Vision/Gemini). Prompt: "Is this image related to [Task Title]?"
4.	Result:
o	AI duyệt: Đánh dấu verified = true, cộng thêm thưởng.
o	AI từ chối: Gửi Notification báo user làm lại.
________________________________________
3. Thiết Kế Database Schema (Overview từ SQL của bạn)
Dựa trên file SQL, đây là sơ đồ quan hệ rút gọn:
•	Auth: users (có cột rpg stats: level, exp, gold).
•	Core: tasks (1-n) task_instances. tasks (1-1) task_recurrences.
•	Data Organization: notes, tags (Polymorphic: gắn được vào task lẫn note).
•	Inventory: items (n-n) users (thông qua user_items có quantity).
•	Social: friendships (status: pending/accepted), activity_feed (JSON data).
________________________________________
4. Implementation Guide (Laravel 12 & Pattern)
A. Service Layer (Xử lý logic nghiệp vụ)
Tạo app/Services/GamificationService.php:
PHP
class GamificationService {
    public function processTaskReward(User $user, TaskInstance $instance) {
        // 1. Tính toán
        $baseExp = 10;
        $multiplier = match($instance->priority->value) {
            'high' => 2.0, 'medium' => 1.5, default => 1.0
        };
        $earnedExp = $baseExp * $multiplier;
        
        // 2. Cộng thưởng (Transaction DB)
        DB::transaction(function() use ($user, $earnedExp) {
            $user->gainExp($earnedExp); // Hàm này nằm trong User Model (đã làm ở bước trước)
            $user->addCoins(rand(5, 15));
        });

        // 3. Broadcast Real-time
        TaskCompletedEvent::dispatch($user, $earnedExp);
    }
}
B. Notification System (Đa kênh)
Sử dụng Laravel Notification System.
•	Database: Lưu vào bảng notifications để hiện hình quả chuông trên web.
•	 Hiển thị Toast popup ngay lập tức.
•	Mail: Gửi email tổng kết tuần (Weekly Report).
PHP
class TaskCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via($notifiable)
    {
        return ['database', 'broadcast']; // Lưu DB và bắn Socket
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => "Bạn vừa nhận được {$this->exp} EXP!",
            'type' => 'success'
        ]);
    }
}
C. Chat System (Real-time)
•	Table: Cần thêm bảng messages (id, sender_id, receiver_id, content, created_at).
•	Frontend: Khi user A nhắn, user B (nếu đang online) sẽ nhận event qua WebSocket và append tin nhắn vào UI mà không cần reload.
5. Lộ trình phát triển (Next Steps)
1.	Phase 1 (MVP Core):
o	Hoàn thiện Authentication (Sanctum).
o	CRUD Task & Note (dùng cấu trúc Repository đã refactor).
o	Logic sinh Task hằng ngày (Task Scheduler).
o	Tính năng Check done -> Cộng EXP/Gold cơ bản.
2.	Phase 2 (Gamification & Social):
o	Shop mua vật phẩm (trừ Gold trong DB).
o	Inventory (trang bị vật phẩm).
o	Profile User (khoe level, items).
3.	Phase 3 (Advanced & AI):
o	Tích hợp Chat/Noti realtime.
o	Tích hợp AI check ảnh proof.
o	Leaderboard (bảng xếp hạng) dùng Redis ZSET để tối ưu tốc độ.

