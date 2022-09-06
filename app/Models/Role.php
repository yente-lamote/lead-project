<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function employees(){
        return $this->belongsToMany(User::class,'company_employee_role')->using(CompanyEmployeeRole::class)->withPivot('company_id');
    }
}
