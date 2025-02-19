<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    use HasFactory;

    protected $table   = "models";
    protected $guarded = [];
    protected $appends = ['name'];
    protected $casts   = [
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'date:Y-m-d'
    ];
    public function getNameAttribute()
    {
        return $this->attributes['name_'. getLocale()];
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function cars()
    {
        return $this->hasMany(Car::class,'model_id');
    }
    public function countCars()
    {
        return $this->hasMany(Car::class,'model_id')->where('publish',1)->where('show_in_home_page', 1);
    }

    public function availableYears()
{
    return $this->cars()
        ->pluck('year')
        ->unique()
        ->sort()
        ->values();
}


public function availableTypes()
{
    return $this->cars()
        ->pluck('is_new')
        ->unique()
        ->map(function ($isNew) {
            return [
                'value' => $isNew,
                'title' => $isNew==1?__('New'):__('Used'),
            ];
        })
        ->values();
}

public function availableTypesByYear($year)
{
    return $this->cars()
        ->where('year', $year) // ✅ Filter by selected year
        ->pluck('is_new')
        ->unique()
        ->map(function ($isNew) {
            return [
                'value' => $isNew,
                'title' => $isNew == 1 ? __('New') : __('Used'),
            ];
        })
        ->values();
}


public function availableGearShiftersByYearAndType($year, $isNew)
{
    return $this->cars()
        ->where('year', $year)
        ->where('is_new', $isNew)
        ->pluck('gear_shifter') // Assuming column is `gear_shifter`
        ->unique()
        ->map(function ($gear) {
            return [
                'value' => $gear,
                'title' => $gear == 'automatic' ? __('Automatic') : __('Manual'),
            ];
        })
        ->values();
}


public function availableColorsByYearTypeAndGear($year, $isNew, $gearShifter)
{
    return $this->cars()
        ->where('year', $year)
        ->where('is_new', $isNew)
        ->where('gear_shifter', $gearShifter)
        ->with('color') // Assuming 'colors' is the relationship to `car_color` table
        ->get()
        ->pluck('color') // Get colors from relationship
        ->flatten()
        ->unique('id')
        ->map(function ($color) {
            return [
                'value' => $color->id,
                'title' => $color->name, // Assuming 'name' holds color name
            ];
        })
        ->values();
}



public function availableGearshifters()
{
    return $this->cars()
        ->pluck('gear_shifter')
        ->unique()
        ->map(function ($gearshifter) {
            return [
                'value' => $gearshifter,
                'title' => $gearshifter
            ];
        })
        ->values();
}


public function availableColors()
{
    return $this->cars()
        ->with('color')
        ->get()
        ->flatMap(function ($car) {
            return $car->color;
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
