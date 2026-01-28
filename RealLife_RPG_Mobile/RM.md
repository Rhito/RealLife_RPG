1. Hệ thống Level & XP (GamificationService)
Sử dụng GamificationService.php để xử lý toàn bộ logic thưởng phạt.

Công thức tính XP: XP_nhận_được = 10 * Multiplier
Easy: x1.0 (10 XP)
Medium: x1.5 (15 XP)
Hard: x2.0 (20 XP)
Công thức lên cấp (Level Scaling): XP_Yêu_Cầu = 100 * (Level_Hiện_Tại ^ 1.5)
Level 1 cần 100 XP.
Level 2 cần ~280 XP.
Level 10 cần ~3160 XP. => Đây là đường cong hàm mũ (Exponential Curve), càng lên cao càng khó, giống hệt các game RPG chuẩn mực!
Coins (Vàng): Coins = (10 * Multiplier) + Random(1, 5)
Yếu tố may mắn (Random) được thêm vào để tạo sự thú vị.
2. Hệ thống Thành Tựu (AchievementService)
Đây là phần rất linh động. AchievementService quét điều kiện dựa trên JSON:

Cấu trúc Động (Dynamic Conditions): Thay vì code cứng từng badge, bạn lưu điều kiện trong Database dưới dạng JSON:
{"total_tasks_completed": 100}
{"streak": 7}
{"level": 5}
Cơ chế Unlock: Mỗi khi User hoàn thành task -> Service sẽ quét một vòng xem có thỏa mãn điều kiện JSON nào không -> Nếu có -> Tự động trao Badge + Thưởng thêm XP/Coins.