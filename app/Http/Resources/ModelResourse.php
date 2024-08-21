<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ModelResourse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'title'=> getLocale() == 'ar' ? $this->name_ar : $this->name_en ,
            'cars_count' => $this->countCars->count(), // Directly count the related cars

            'Categories' => $this->categories->map(function ($categorie) {
                return [
                    'id'=>$categorie->id,
                    'name'=>$categorie->name,
                ];
            })->toArray(),

        ];
    }
}
