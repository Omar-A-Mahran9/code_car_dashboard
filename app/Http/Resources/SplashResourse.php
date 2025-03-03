<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SplashResourse extends JsonResource
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
            'title_ar'=> $this->name_ar,
            'title_en'=> $this->name_en,
            'description'=> $this->description,
            'description_ar'=> $this->description_ar,
            'description_en'=> $this->description_en,

            'image'=>getImagePathFromDirectory($this->image,'Splashes'),

        ];
    }
}
