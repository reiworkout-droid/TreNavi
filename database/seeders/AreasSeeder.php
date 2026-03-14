<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreasSeeder extends Seeder
{
    public function run()
    {
        $areas = [
            // --- 福岡市 ---
            ['id'=>1, 'name'=>'天神・大名・今泉', 'city_id'=>32], // 福岡市中央区
            ['id'=>2, 'name'=>'薬院・平尾', 'city_id'=>32],
            ['id'=>3, 'name'=>'赤坂・舞鶴', 'city_id'=>32],
            ['id'=>4, 'name'=>'中洲・春吉', 'city_id'=>33],      // 追加
            ['id'=>5, 'name'=>'博多駅周辺', 'city_id'=>33],       // 福岡市博多区
            ['id'=>6, 'name'=>'福岡市南区全域', 'city_id'=>34],
            ['id'=>7, 'name'=>'福岡市東区全域', 'city_id'=>35],
            ['id'=>8, 'name'=>'福岡市早良区全域', 'city_id'=>36],
            ['id'=>9, 'name'=>'北九州市小倉北区全域', 'city_id'=>37],
            ['id'=>10, 'name'=>'北九州市小倉南区全域', 'city_id'=>38],
            ['id'=>11, 'name'=>'北九州市戸畑区全域', 'city_id'=>39],
            ['id'=>12, 'name'=>'北九州市若松区全域', 'city_id'=>40],
            ['id'=>13, 'name'=>'北九州市八幡東区全域', 'city_id'=>41],
            ['id'=>14, 'name'=>'北九州市八幡西区全域', 'city_id'=>42],
            ['id'=>15, 'name'=>'春日市全域', 'city_id'=>43],
            ['id'=>16, 'name'=>'大野城市全域', 'city_id'=>44],
            ['id'=>17, 'name'=>'那珂川市全域', 'city_id'=>45],
            ['id'=>18, 'name'=>'筑紫野市全域', 'city_id'=>46],
            ['id'=>19, 'name'=>'小郡市全域', 'city_id'=>47],
            ['id'=>20, 'name'=>'久留米市全域', 'city_id'=>48],
            ['id'=>21, 'name'=>'糸島市全域', 'city_id'=>49],
            ['id'=>22, 'name'=>'吉富町全域', 'city_id'=>50],

            // --- 東京23区（主要エリア分け） ---
            ['id'=>23, 'name'=>'渋谷・原宿・表参道', 'city_id'=>13], // 渋谷区
            ['id'=>24, 'name'=>'新宿・歌舞伎町', 'city_id'=>4],     // 新宿区
            ['id'=>25, 'name'=>'池袋・目白', 'city_id'=>20],       // 豊島区
            ['id'=>26, 'name'=>'銀座・京橋', 'city_id'=>2],        // 中央区
            ['id'=>27, 'name'=>'六本木・麻布・赤坂', 'city_id'=>3], // 港区
            ['id'=>28, 'name'=>'上野・御徒町', 'city_id'=>6],     // 台東区
            ['id'=>29, 'name'=>'北千住・綾瀬', 'city_id'=>21],     // 足立区

            // --- 大阪市 ---
            ['id'=>30, 'name'=>'梅田・北区', 'city_id'=>24],
            ['id'=>31, 'name'=>'心斎橋・南船場', 'city_id'=>25],
            ['id'=>32, 'name'=>'天王寺・阿倍野', 'city_id'=>26],
            ['id'=>33, 'name'=>'堺市全域', 'city_id'=>28],

            // --- 名古屋市 ---
            ['id'=>34, 'name'=>'栄・久屋大通', 'city_id'=>29],
            ['id'=>35, 'name'=>'名古屋駅周辺', 'city_id'=>29],
            ['id'=>36, 'name'=>'金山・大須', 'city_id'=>30],
            ['id'=>37, 'name'=>'名古屋市天白区全域', 'city_id'=>31],
        ];

        DB::table('areas')->insert($areas);
    }
}