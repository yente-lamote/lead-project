<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\CompanyEmployeeRole;
use App\Models\User;
use App\Models\Role;

use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Company $company){
        return $company->employees->contains($user);
    }

    public function update(User $user, Company $company){
        $roleId= Role::where('name','Owner')->first()->id;
        return CompanyEmployeeRole::where('company_id',$company->id)->where('user_id',$user->id)->where('role_id',$roleId)->first()!==null;
    }

    public function manageEmployees(User $user, Company $company,User $employee){
        $ownerRoleId= Role::where('name','Owner')->first()->id;
        return $company->employees->contains($user)&&$user->id!==$employee->id&&$user->getRole($company)->id===$ownerRoleId && $employee->getRole($company)->id!==$ownerRoleId;
    }

}
