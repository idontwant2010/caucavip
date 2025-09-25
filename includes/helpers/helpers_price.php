<?php
// helpers_price.php

if (!function_exists('money_vnd')) {
    function money_vnd(int|float $v): string {
        $neg = $v < 0 ? '-' : '';
        return $neg . number_format(abs($v), 0, ',', '.') . ' đ';
    }
}

/**
 * Tính số suất và phút lẻ.
 * - Mặc định: nếu >0 phút thì tối thiểu 1 suất (đúng khái niệm "giá theo suất").
 * - Nếu bạn muốn cho phép < base_duration vẫn tính theo phút, set $minimumOneSuat=false.
 */
if (!function_exists('calc_suat')) {
    function calc_suat(int $tong_phut, int $base_duration, bool $minimumOneSuat = true): array {
        if ($tong_phut <= 0) return [0, 0];
        $so_suat_raw = intdiv($tong_phut, $base_duration);
        if ($minimumOneSuat) {
            $so_suat = max(1, $so_suat_raw);
            $phut_le = max(0, $tong_phut - $so_suat * $base_duration);
        } else {
            $so_suat = $so_suat_raw;
            $phut_le = $tong_phut % $base_duration;
        }
        return [$so_suat, $phut_le];
    }
}

/**
 * Tính tiền câu cá thịt (theo suất + phút lẻ) + bán/thu cá + trừ đặt cọc.
 * Discount 2x/3x/4x là SỐ TIỀN CỐ ĐỊNH, không phải %.
 *
 * $gia: row từ gia_ca_thit_phut (base_duration, base_price, extra_unit_price,
 *      discount_2x_duration, discount_3x_duration, discount_4x_duration,
 *      gia_ban_ca, gia_thu_lai)
 */
if (!function_exists('calcTienCauThit')) {
    function calcTienCauThit(
        array $gia,
        int $tong_phut,
        float $kg_cau,
        float $kg_ban,
        float $kg_thu,
        int $booking_amount = 0,
        bool $repeatDiscountOver4 = false,
        bool $minimumOneSuat = true
    ): array {
        $base_duration = (int)$gia['base_duration'];
        $base_price    = (int)$gia['base_price'];
        $extra_price   = (int)$gia['extra_unit_price'];

        $d2 = (int)$gia['discount_2x_duration'];
        $d3 = (int)$gia['discount_3x_duration'];
        $d4 = (int)$gia['discount_4x_duration'];

        $gia_ban_ca  = (int)$gia['gia_ban_ca'];   // vnd/kg (mang về)
        $gia_thu_lai = (int)$gia['gia_thu_lai'];  // vnd/kg (hồ thu lại)

        // 1) Số suất & phút lẻ
        [$so_suat, $phut_le] = calc_suat($tong_phut, $base_duration, $minimumOneSuat);

        // 2) Tiền theo suất
        $tien_suat = $so_suat * $base_price;

        // 3) Discount theo mốc suất (cố định tiền, không %)
        $discount = 0;
        if ($so_suat >= 4) {
            if ($repeatDiscountOver4) {
                $discount = ($so_suat - 3) * $d4; // 4..n ⇒ áp dụng (n-3) lần
            } else {
                $discount = $d4; // cap 1 lần
            }
        } elseif ($so_suat === 3) {
            $discount = $d3;
        } elseif ($so_suat === 2) {
            $discount = $d2;
        }

        // 4) Tiền thêm phút lẻ
        $tien_them = $phut_le * $extra_price;

        // 5) Thành tiền trước phần cá
        $real_amount_before_fish = $tien_suat + $tien_them - $discount;

        // 6) Tiền cá
        $fish_sell_amount   = $kg_ban * $gia_ban_ca;   // + tiền
        $fish_return_amount = $kg_thu * $gia_thu_lai;  // - tiền

        // 7) Tổng cần thanh toán (âm = hồ trả ngược khách)
        $total_amount = $real_amount_before_fish + $fish_sell_amount - $fish_return_amount - $booking_amount;

        return [
            'so_suat' => $so_suat,
            'phut_le' => $phut_le,
            'tien_suat' => $tien_suat,
            'tien_them' => $tien_them,
            'discount' => $discount,
            'real_amount_before_fish' => $real_amount_before_fish,
            'fish_sell_amount' => $fish_sell_amount,
            'fish_return_amount' => $fish_return_amount,
            'booking_amount' => $booking_amount,
            'total_amount' => $total_amount,
        ];
    }
}
