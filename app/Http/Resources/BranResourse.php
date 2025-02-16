<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BranResourse extends JsonResource
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
            'id' => $this->id,
            'title'=> $this->name,
            'image' => getImagePathFromDirectory($this->image, 'Brands'),
            'cover' => getImagePathFromDirectory($this->cover, 'Brands'),
            'description_of_page' => settings()->getSettings('brand_text_in_home_page_' . getLocale()),

            'models' => $this->models->map(function ($model) {
                return [
                    'id'=>$model->id,
                    'name'=>$model->name,
                    'cars_count' => $model->countCars->count(), // Directly count the related cars
                    'Categories' => $model->categories->map(function ($categorie) {
                        return [
                            'id'=>$categorie->id,
                            'name'=>$categorie->name,
                        ];
                    })->toArray()
                ];
            })->toArray(),
            'cars_count' => $this->countCars->count(), // Directly count the related cars

         ];
    }
}
