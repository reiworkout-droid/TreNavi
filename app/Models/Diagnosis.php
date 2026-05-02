<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

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
     * 数値からタイプを判定するロジック 
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
}
