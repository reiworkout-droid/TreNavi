<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;

class Diagnosis extends Model
{
    //
    protected $fillable = [
        'user_id',
        'style',
        'talk',
        'logic',
        'pace',
        'distance',
    ];

    // JSON変換時に一緒に診断結果を含める
        protected $appends = ['user_type'];

    /**
     * 【表示用】数値からタイプを判定するロジック 
     */
    protected function userType(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->style >= 4 && $this->logic >= 4 && $this->pace >= 4 && $this->distance >= 3) {
                    return "ストイック型";
                }
                if ($this->style <= 2 && $this->talk >= 4 && $this->pace <= 3 && $this->distance <= 2) {
                    return "エンジョイ型";
                }
                if ($this->style <= 2 && $this->distance <= 2 && $this->talk >= 3) {
                    return "サポート重視型";
                }
                if ($this->talk <= 2 && $this->pace <= 2) {
                    return "マイペース型";
                }

                return "バランス型";
            },
        );
    }
    /**
     * 【検索用】指定のタイプ名で絞り込む（クエリスコープ）
     */
    public function scopeOfUserType(Builder $query, string $type): Builder
    {
        $condStoic = fn($q) => $q->where('style', '>=', 4)->where('logic', '>=', 4)->where('pace', '>=', 4)->where('distance', '>=', 3);
        $condEnjoy = fn($q) => $q->where('style', '<=', 2)->where('talk', '>=', 4)->where('pace', '<=', 3)->where('distance', '<=', 2);
        $condSupport = fn($q) => $q->where('style', '<=', 2)->where('distance', '<=', 2)->where('talk', '>=', 3);
        $condMypace = fn($q) => $q->where('talk', '<=', 2)->where('pace', '<=', 2);

        return match ($type) {
            // ストイック型
            'ストイック型' => $query->where($condStoic),

            // エンジョイ型：ストイックではない
            'エンジョイ型' => $query->where($condEnjoy)->whereNot($condStoic),

            // サポート重視型：ストイックでもエンジョイでもない
            'サポート重視型' => $query->where($condSupport)
                                    ->whereNot($condStoic)
                                    ->whereNot($condEnjoy),

            // マイペース型：上位3つすべてに当てはまらない
            'マイペース型' => $query->where($condMypace)
                                    ->whereNot($condStoic)
                                    ->whereNot($condEnjoy)
                                    ->whereNot($condSupport),

            // バランス型：すべての条件に当てはまらない
            'バランス型' => $query->whereNot($condStoic)
                                ->whereNot($condEnjoy)
                                ->whereNot($condSupport)
                                ->whereNot($condMypace),

            default => $query,
        };
    }    
}
