<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
     protected $guarded = [];
    protected $appends = [ 'name' ];
    protected $casts   = [
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'date:Y-m-d'
    ];
    public function getNameAttribute()
    {
        return $this->attributes['name_' . getLocale() ];
    }



    public function models() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CarModel::class);
    }

    public function cars() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Car::class);
    }

    public function oldCars() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Car::class)->where("is_new" , 0);
    }

    public function newCars() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Car::class)->where("is_new" , 1);
    }

    // public function countCars()
    // {
    //     return $this->cars->count();
    // }

    public function countCars()
    {
        return $this->hasMany(Car::class)->where('publish',1)->where('show_in_home_page', 1);
    }

    public function availableYears()
{
    return $this->models()
        ->with('cars')
        ->get()
        ->flatMap(function ($model) {
            return $model->cars->pluck('year'); // ✅ Extract years from cars
        })
        ->unique()
        ->sort()
        ->values();
}

public function availableTypes()
{
    return $this->models()
        ->with('cars')
        ->get()
        ->flatMap(function ($model) {
            return $model->cars->pluck('is_new');
        })
        ->unique()
        ->map(function ($isNew) {
            return [
                'value' => $isNew,
                'title' => $isNew==1?__('New'):__('Used'),
            ];
        })
        ->values();
}

public function availableGearshifters()
{
    return $this->models()
        ->with('cars')
        ->get()
        ->flatMap(function ($model) {
            return $model->cars->pluck('gear_shifter');
        })
        ->unique()
        ->map(function ($gearshifter) {
            return [
                'value' => $gearshifter,
                'title' => $gearshifter // Capitalize first letter
            ];
        })
        ->values();
}


public function availableColors()
{
    return $this->models()
        ->with('cars.color')
        ->get()
        ->flatMap(function ($model) {
            return $model->cars->flatMap(function ($car) {
                return $car->color;
            });
        })
        ->unique('id') // Ensure unique colors
        ->map(function ($color) {
            return [
                'value' => $color->id,
                'title' => $color->name
            ];
        })
        ->values();
}





}
