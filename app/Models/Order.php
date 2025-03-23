<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Order extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = ['id','name','email', 'phone','sex','employee_id','price','nationality_id','identity_no', 'car_name', 'car_id','color_id', 'city_id', 'type', 'identity_Card', 'License_Card','Hr_Letter_Image','Insurance_Image','status_id', 'client_id','opened_at','opened_by','birth_date','verified','verified_at','old_order_id','edited','edited_by','more_details','car_details','type_of_order'];
    protected $casts   = [
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'date:Y-m-d'
    ];
    public function orderDetailsCar()
    {
        return $this->hasOne(CarOrder::class, 'order_id', 'id'); // Ensure 'order_id' is the correct foreign key
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class)->withTrashed();
    }



        public function color()
    {
        return $this->belongsTo(Color::class);
    }


    public function bank()
    {
        return $this->hasOne(Bank::class,'id','bank_id');
    }

    public function statusHistory()
    {
        return $this->hasMany( OrderHistory::class)->with('employee');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class,'opened_by');
    }
    public function employee_edit()
    {
        return $this->belongsTo(Employee::class,'edited_by');
    }

      public function finance_approval()
    {
        return $this->hasMany(FinanceApproval::class);
    }

    public function statue()
    {
        return $this->belongsTo(SettingOrderStatus::class,'status_id');
    }
    public function relatedOrders()
        {
            return $this->hasMany(Order::class, 'old_order_id', 'id');
        }

    public function sendOTP()
    {
        // $this->verification_code = rand(1111, 9999);
        $this->verification_code ="2244";

        $appName                 = settings()->getSettings("website_name_" . getLocale()) ?? "CodeCar";
        // $this->sendSMS("$appName: $this->verification_code هو رمز الحماية,لا تشارك الرمز");
        OtpLink($this->phone,$this->verification_code);

        $this->save();
    }


    public function verifyOTP($verification_code)
    {
        if ($this->verification_code === $verification_code)
        {
            $this->verified_at       = now();
            $this->verified       = true;
            $this->verification_code = NULL;
            $this->save();
            return TRUE;
        } else
        {
            return FALSE;
        }
    }
    public function nationality()
    {
        return $this->belongsTo(Nationality::class ,);
    }

}
