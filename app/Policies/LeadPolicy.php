<?php

namespace App\Policies;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeadPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Lead $lead){
        if($lead->company->employees->contains($user))return true;
        foreach($lead->companies as $company){
            if($company->employees->contains($user)){
                return true;
            }
        }
        return false;
    }

    public function view(User $user, Lead $lead){
        if($lead->company->employees->contains($user))return true;
        foreach($lead->companies as $company){
            if($company->employees->contains($user)){
                return true;
            }
        }
        return false;
    }
}
