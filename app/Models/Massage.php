<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Massage extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts   = [
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'date:Y-m-d'
    ];
    public function recipients()
    {
        return $this->belongsToMany(Employee::class, 'massage_employee', 'massage_id', 'employee_id');
    }
    public function sender()
    {
        return $this->belongsTo(Employee::class, 'from');
    }
    
}
