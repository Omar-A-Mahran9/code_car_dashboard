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
                  'available_years' => $model->availableYears()->map(function ($year) use ($model) {
                        return [
                            'year' => $year,
                            'available_types' => $model->availableTypesByYear($year)->map(function ($type) use ($model, $year) {
                                return [
                                    'value' => $type['value'],
                                    'title' => $type['title'],
                                    'available_type_gearshifter' => $model->availableGearShiftersByYearAndType($year, $type['value'])->map(function ($gear) use ($model, $year, $type) {
                                        return [
                                            'value' => $gear['value'],
                                            'title' => $gear['title'],
                                            'available_colors' => $model->availableColorsByYearTypeAndGear($year, $type['value'], $gear['value']) // ✅ Fetch available colors
                                        ];
                                    })
                                ];
                            })
                        ];
                    }),

                    // 'available_type' => $model->availableTypes(),
                    // 'available_type_gearshifter' => $model->availableGearshifters(),
                    'available_colors' => $model->availableColors(),

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
