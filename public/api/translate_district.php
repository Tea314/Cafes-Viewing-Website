<?php

function translateDistrictName($district_en)
{
    // Lưu kết quả dịch để tránh lặp lại
    static $translated = [];
    if (isset($translated[$district_en])) {
        return $translated[$district_en];
    }

    // Dịch các tên quận dạng "District X"
    if (preg_match('/District\s+(\d+)/i', $district_en, $matches)) {
        $translated[$district_en] = 'Quận '.$matches[1];

        return $translated[$district_en];
    }

    // Ánh xạ các tên quận đặc biệt
    $mapping = [
        'Binh Thanh District' => 'Quận Bình Thạnh',
        'Phu Nhuan District' => 'Quận Phú Nhuận',
        'Go Vap District' => 'Quận Gò Vấp',
        'Tan Binh District' => 'Quận Tân Bình',
        'Tan Phu District' => 'Quận Tân Phú',
        'Thu Duc District' => 'Quận Thủ Đức',
        'District 7' => 'Quận 7',
        'District 4' => 'Quận 4',
    ];

    $translated[$district_en] = $mapping[$district_en] ?? $district_en;

    return $translated[$district_en];
}
