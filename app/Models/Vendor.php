<?php

namespace App\Models;

use App\Enums\VendorStatus;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Vendor extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $guarded = [];
    protected $appends = ['status_name','image_url','translated_type'];
     
    protected $casts = [
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'date:Y-m-d'
    ];
    protected $hidden = ['password', 'remember_token'];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function getStatusNameAttribute()
    {
        return __(ucfirst(VendorStatus::tryFrom($this->status)->name));
    }

    public function getImageUrlAttribute()
    {
      
        return getImagePathFromDirectory($this->image,'Vendors');
    }
    
       // Add the translated type accessor
    public function getTranslatedTypeAttribute()
    {
 
        $contentLanguage = request()->get('content_language', getLocale()); // Default to 'en' if not set
        return $this->translateType($this->type, $contentLanguage);
    }

    private function translateType($type, $contentLanguage)
    {
        $typeMappings = [
            'en' => [
                'individual' => 'Individual',
                'company' => 'Company', // Add other mappings as needed
            ],
            'ar' => [
                'individual' => 'فردي',
                'exhibition' => 'معرض', // Add other mappings as needed
                'agency' => 'وكالة', // Add other mappings as needed

            ],
            // Add more languages as needed
        ];

        return $typeMappings[$contentLanguage][$type] ?? $type;
    }

    public function sendOTP()
    {
        $this->verification_code = rand(1111, 9999);
        $appName                 = settings()->getSettings("website_name_" . getLocale()) ?? "CodeCar";
        // $this->sendSMS("$appName: $this->verification_code هو رمز الحماية,لا تشارك الرمز");
        OtpLink($this->phone,$this->verification_code);

        $this->save();
    }

    public function verifyOTP($verification_code)
    {
        if ($this->verification_code == $verification_code)
        {
            $this->verified_at       = now();
            $this->verification_code = NULL;
            $this->save();
            return TRUE;
        } else
        {
            return FALSE;
        }
        return FALSE;
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function vendor()
    {
        return $this->hasMany(Car::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
    
      public function employee()
    {
        return $this->belongsTo(Employee::class);
    }


}
