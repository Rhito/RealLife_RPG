import { Dimensions, Platform, StatusBar } from 'react-native';

const { width: SCREEN_WIDTH, height: SCREEN_HEIGHT } = Dimensions.get('window');

// Base dimensions (iPhone 11 Pro as reference)
const baseWidth = 375;
const baseHeight = 812;

/**
 * Width percentage to DP
 * Tính toán kích thước responsive dựa trên % chiều rộng màn hình
 * @param percentage - Phần trăm chiều rộng (0-100)
 * @returns Giá trị pixel tương ứng
 */
export const wp = (percentage: number): number => {
  const value = (percentage * SCREEN_WIDTH) / 100;
  return Math.round(value);
};

/**
 * Height percentage to DP
 * Tính toán kích thước responsive dựa trên % chiều cao màn hình
 * @param percentage - Phần trăm chiều cao (0-100)
 * @returns Giá trị pixel tương ứng
 */
export const hp = (percentage: number): number => {
  const value = (percentage * SCREEN_HEIGHT) / 100;
  return Math.round(value);
};

/**
 * Scale font size theo kích thước màn hình
 * @param size - Kích thước font gốc
 * @returns Kích thước font đã scale
 */
export const scaleFont = (size: number): number => {
  const scale = SCREEN_WIDTH / baseWidth;
  const newSize = size * scale;
  // Giới hạn scale để font không quá lớn hoặc quá nhỏ
  if (newSize < size * 0.9) return Math.round(size * 0.9);
  if (newSize > size * 1.3) return Math.round(size * 1.3);
  return Math.round(newSize);
};

/**
 * Responsive spacing system
 * Sử dụng: spacing.md thay vì 16
 */
export const spacing = {
  xs: wp(2),   // ~7-8px
  sm: wp(3),   // ~11-12px
  md: wp(4),   // ~15-16px
  lg: wp(5),   // ~19-20px
  xl: wp(6),   // ~22-24px
  xxl: wp(8),  // ~30-32px
};

/**
 * Phát hiện loại màn hình
 */
export const isSmallDevice = SCREEN_WIDTH < 375;
export const isMediumDevice = SCREEN_WIDTH >= 375 && SCREEN_WIDTH < 414;
export const isLargeDevice = SCREEN_WIDTH >= 414;

/**
 * Get screen dimensions
 */
export const getScreenDimensions = () => ({
  width: SCREEN_WIDTH,
  height: SCREEN_HEIGHT,
  isSmall: isSmallDevice,
  isMedium: isMediumDevice,
  isLarge: isLargeDevice,
});

/**
 * Moderate scale - tốt cho các giá trị cần scale vừa phải
 * @param size - Kích thước gốc
 * @param factor - Hệ số điều chỉnh (mặc định 0.5)
 */
export const moderateScale = (size: number, factor: number = 0.5): number => {
  const scale = SCREEN_WIDTH / baseWidth;
  return Math.round(size + (scale - 1) * size * factor);
};
