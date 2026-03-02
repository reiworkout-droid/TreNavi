<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrainerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     */
public function toArray($request)
{
    return [
        'id' => $this->id,
        'name' => $this->name,
        'profile_image' => $this->profile_image,

        'areas' => $this->areas->pluck('name'),

        'categories' => $this->categories->pluck('name'),

        'specialities' => $this->specialities->pluck('name'),
    ];
}}
