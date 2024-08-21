<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarOrder extends Model
{
    use HasFactory;
 
    protected $table = 'cars_orders';
    protected $guarded = ['name', 'phone', 'price', 'car_name', 'car_id', 'city_id', 'status', 'client_id'];
    protected $casts   = [
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'date:Y-m-d'
    ];

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
    public function organization_activity()
    {
        return $this->belongsTo(Organizationactive::class);
    }
    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }
    public function bank_offer(){
        return $this->belongsTo(BankOffer::class,'bank_offer_id');

        
    }
   
}
