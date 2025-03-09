<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BrandsResourseOnly extends JsonResource
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
            'count_cars_count' => $this->countCars->count(), // Directly count the related cars
            'car_available_types'=>$this->car_available_types,

         ];
    }


 
}
