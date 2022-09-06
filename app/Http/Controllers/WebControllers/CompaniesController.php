<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompaniesController extends Controller
{
    //
    public function index(){
        $companies = auth()->user()->companies;
        return view('companies.index', compact('companies'));
    }

    public function show(Company $company){
        $this->authorize('view',$company);
        return view('companies.show', compact('company'));
    }
    public function edit(Company $company){
        return view('companies.edit',compact('company'));
    }
    public function update(Company $company){
        $this->authorize('update',$company);
        $company->update($this->validateRequest());        
        if(request()->image){
            request()->validate(['image' => 'mimes:png']);
            request()->image->move(public_path("assets/images/companies"), $company->id.'.png');
        }
        return response('updated',204);
    }

    protected function validateRequest(){
        return request()->validate([
            'name' => 'sometimes',
            'description' => 'sometimes',
        ]);
    }

    public function activityLog(Company $company){
        $this->authorize('view',$company);
        return view('companies.activities',compact('company'));
    }
}
