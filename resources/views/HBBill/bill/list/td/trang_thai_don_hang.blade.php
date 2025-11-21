@if($item->trang_thai_mua_hang == 'dat_don_hang_thanh_cong')
    {{ __('lang.' . $item->trang_thai_mua_hang) }}
@elseif($item->trang_thai_mua_hang == 'cho_xac_nhan')
    {{ __('lang.' . $item->trang_thai_mua_hang) }}
@elseif($item->trang_thai_mua_hang == 'thanh_toan_thanh_cong')
    {{ __('lang.' . $item->trang_thai_mua_hang) }}
@elseif($item->trang_thai_mua_hang == 'don_hang_dang_duoc_dong_goi')
    {{ __('lang.' . $item->trang_thai_mua_hang) }}
@elseif($item->trang_thai_mua_hang == 'don_hang_dang_roi_kho')
    {{ __('lang.' . $item->trang_thai_mua_hang) }}
@elseif($item->trang_thai_mua_hang == 'dang_van_chuyen')
    {{ __('lang.' . $item->trang_thai_mua_hang) }}
@elseif($item->trang_thai_mua_hang == 'da_nhan_hang_thanh_cong')
    {{ __('lang.' . $item->trang_thai_mua_hang) }}
@elseif($item->trang_thai_mua_hang == 'huy_don_hang')
    {{ __('lang.' . $item->trang_thai_mua_hang) }}
@elseif($item->trang_thai_mua_hang == 'hoan_tra')
    {{ __('lang.' . $item->trang_thai_mua_hang) }}
@else
    hum
@endif



