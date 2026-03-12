<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitiesSeeder extends Seeder
{
    public function run()
    {
        $cities = [
            // 東京23区
            ['id'=>1, 'name'=>'千代田区', 'prefecture_id'=>13],
            ['id'=>2, 'name'=>'中央区', 'prefecture_id'=>13],
            ['id'=>3, 'name'=>'港区', 'prefecture_id'=>13],
            ['id'=>4, 'name'=>'新宿区', 'prefecture_id'=>13],
            ['id'=>5, 'name'=>'文京区', 'prefecture_id'=>13],
            ['id'=>6, 'name'=>'台東区', 'prefecture_id'=>13],
            ['id'=>7, 'name'=>'墨田区', 'prefecture_id'=>13],
            ['id'=>8, 'name'=>'江東区', 'prefecture_id'=>13],
            ['id'=>9, 'name'=>'品川区', 'prefecture_id'=>13],
            ['id'=>10, 'name'=>'目黒区', 'prefecture_id'=>13],
            ['id'=>11, 'name'=>'大田区', 'prefecture_id'=>13],
            ['id'=>12, 'name'=>'世田谷区', 'prefecture_id'=>13],
            ['id'=>13, 'name'=>'渋谷区', 'prefecture_id'=>13],
            ['id'=>14, 'name'=>'中野区', 'prefecture_id'=>13],
            ['id'=>15, 'name'=>'杉並区', 'prefecture_id'=>13],
            ['id'=>16, 'name'=>'豊島区', 'prefecture_id'=>13],
            ['id'=>17, 'name'=>'北区', 'prefecture_id'=>13],
            ['id'=>18, 'name'=>'荒川区', 'prefecture_id'=>13],
            ['id'=>19, 'name'=>'板橋区', 'prefecture_id'=>13],
            ['id'=>20, 'name'=>'練馬区', 'prefecture_id'=>13],
            ['id'=>21, 'name'=>'足立区', 'prefecture_id'=>13],
            ['id'=>22, 'name'=>'葛飾区', 'prefecture_id'=>13],
            ['id'=>23, 'name'=>'江戸川区', 'prefecture_id'=>13],

            // 大阪圏（主要区のみ）
            ['id'=>24, 'name'=>'大阪市北区', 'prefecture_id'=>27],
            ['id'=>25, 'name'=>'大阪市中央区', 'prefecture_id'=>27],
            ['id'=>26, 'name'=>'大阪市西区', 'prefecture_id'=>27],
            ['id'=>27, 'name'=>'大阪市天王寺区', 'prefecture_id'=>27],
            ['id'=>28, 'name'=>'堺市堺区', 'prefecture_id'=>27],

            // 名古屋圏（主要区のみ）
            ['id'=>29, 'name'=>'名古屋市中区', 'prefecture_id'=>23],
            ['id'=>30, 'name'=>'名古屋市東区', 'prefecture_id'=>23],
            ['id'=>31, 'name'=>'名古屋市千種区', 'prefecture_id'=>23],

            // 福岡圏（主要区＋周辺市）
            ['id'=>32, 'name'=>'福岡市中央区', 'prefecture_id'=>40],
            ['id'=>33, 'name'=>'福岡市博多区', 'prefecture_id'=>40],
            ['id'=>34, 'name'=>'福岡市南区', 'prefecture_id'=>40],
            ['id'=>35, 'name'=>'福岡市東区', 'prefecture_id'=>40],
            ['id'=>36, 'name'=>'福岡市早良区', 'prefecture_id'=>40],
            ['id'=>37, 'name'=>'北九州市小倉北区', 'prefecture_id'=>40],
            ['id'=>38, 'name'=>'北九州市小倉南区', 'prefecture_id'=>40],
            ['id'=>39, 'name'=>'北九州市戸畑区', 'prefecture_id'=>40],
            ['id'=>40, 'name'=>'北九州市若松区', 'prefecture_id'=>40],
            ['id'=>41, 'name'=>'北九州市八幡東区', 'prefecture_id'=>40],
            ['id'=>42, 'name'=>'北九州市八幡西区', 'prefecture_id'=>40],
            ['id'=>43, 'name'=>'春日市', 'prefecture_id'=>40],
            ['id'=>44, 'name'=>'大野城市', 'prefecture_id'=>40],
            ['id'=>45, 'name'=>'那珂川市', 'prefecture_id'=>40],
            ['id'=>46, 'name'=>'筑紫野市', 'prefecture_id'=>40],
            ['id'=>47, 'name'=>'小郡市', 'prefecture_id'=>40],
            ['id'=>48, 'name'=>'久留米市', 'prefecture_id'=>40],
            ['id'=>49, 'name'=>'糸島市', 'prefecture_id'=>40],
            ['id'=>50, 'name'=>'吉富町', 'prefecture_id'=>40],
        ];

        DB::table('cities')->insert($cities);
    }
}