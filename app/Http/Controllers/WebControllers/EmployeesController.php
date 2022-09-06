<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use App\Models\CompanyEmployeeRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class EmployeesController extends Controller
{

    public function index(Company $company){
        $this->authorize('view',$company);
        $employees = $company->employees;
        return view('employees.index', compact('company','employees'));
    }

    public function dismissEmployee(Company $company, User $employee){
        $this->authorize('manageEmployees',[$company, $employee]);
        $company->remove($employee);
        return Redirect::back()->with('message','Employee dismissed');
    }

    public function changeRole(Company $company, User $employee){
        $this->authorize('manageEmployees',[$company,$employee]);
        $this->validateRole();        
        $employee->companies()->sync([$company->id=> ['role_id' => request()->role_id]]);
        return response('updated',204);
    }

    protected function validateRole(){
        return request()->validate([
            'role_id' => 'exists:roles,id',
        ]);
    }
}
