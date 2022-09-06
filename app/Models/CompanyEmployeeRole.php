<?php
namespace App\Models;

use App\RecordActivity;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Arr;

class CompanyEmployeeRole extends Pivot
{
    use RecordActivity;

    protected $table = 'company_employee_role';

    public function role()
    {
        return $this->belongsTo(Role::class,'role_id');
    }
    public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    }
    public function employee()
    {
        return $this->belongsTo(User::class,'user_id');
    }

}