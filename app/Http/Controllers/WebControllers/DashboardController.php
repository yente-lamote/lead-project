<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;

class DashboardController extends Controller
{
    public function show(Company $company){
        $companies=auth()->user()->companies;
        isset($company->id)?
            $this->authorize('view',$company):
            $company=$companies->first();
        return view('dashboard', compact('company','companies'));
    }
}
