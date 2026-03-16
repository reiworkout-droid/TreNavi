<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\City;
use App\Models\Prefecture;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    /**
     * 都道府県一覧を取得
     */
    public function prefectures()
    {
        return Prefecture::select('id', 'name')->get();
    }

    /**
     * 市区町村一覧を取得（オプションで prefecture_id による絞り込み）
     */
    public function cities(Request $request)
    {
        $query = City::select('id', 'name', 'prefecture_id');

        if ($request->filled('prefecture_id')) {
            $query->where('prefecture_id', $request->prefecture_id);
        }

        return $query->get();
    }

    /**
     * エリア一覧を取得（オプションで city_id による絞り込み）
     */
    public function index(Request $request)
    {
        $query = Area::with('city.prefecture')->select('id', 'name', 'city_id');

        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        }

        return $query->get();
    }

    /**
     * トレーナーが選択している都道府県・市区町村・エリアを取得
     */
    public function trainerAreas(Request $request)
    {
        $trainer = auth()->user()->trainer;

        if (!$trainer) {
            return response()->json(['error' => 'Trainer not found'], 404);
        }

        $areas = $trainer->areas()->with('city.prefecture')->get();

        $area_ids = $areas->pluck('id')->toArray();
        $city_ids = $areas->pluck('city.id')->unique()->toArray();
        $prefecture_ids = $areas->pluck('city.prefecture.id')->unique()->toArray();

        return response()->json([
            'selected_prefecture_ids' => $prefecture_ids,
            'selected_city_ids' => $city_ids,
            'selected_area_ids' => $area_ids,
            'areas' => $areas, // city と prefecture 情報を含む
        ]);
    }
}