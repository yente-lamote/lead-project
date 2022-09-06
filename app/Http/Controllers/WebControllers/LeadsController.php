<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Lead;
use Illuminate\Database\Console\Migrations\StatusCommand;
use Illuminate\Http\Request;

class LeadsController extends Controller
{
    //
    public function index(Company $company){
        $this->authorize('view',$company);
        $leads=$company->accessibleLeads()->paginate(10);
        $companies=auth()->user()->companies;
        return view('leads.index',compact('leads','companies'));
    }
    public function show(Lead $lead){
        $this->authorize('view',$lead);
        return view('leads.show',compact('lead'));
    }
    public function store(){
        $lead = Lead::create(request()->all());
        return response('created',201);
    }

    public function update(Lead $lead){
        $this->authorize('update',$lead);
        $lead->fill($this->validateRequest());
        if(request()->extra_attributes){
            foreach(request()->extra_attributes as $id => $value){
                $lead->attributes()->sync([$id=>[
                    'value'=>$value]
                ],false);
            }
        }
        $lead->update();
        return response('updated',204); 
    }

    public function changeStatus(Lead $lead){
        $this->authorize('update',$lead);
        $lead->setNote(request()->get('note'));
        $lead->update(request()->validate(['status_id'=>'exists:statuses,id|required']));
        return response('updated',204); 
    }

    public function destroy(Lead $lead){
        //dit is een policy
        //in file LeadPoliciy
        //om policy te registreren moet je naar de file authserviceprovider gaan en policy daar toevoegen aan $policies
        $this->authorize('update',$lead);
        $lead->archived=true;
        $lead->save();
        return response('archived',200);
    }

    protected function validateRequest(){
        request()->validate(['extra_attributes'=>['sometimes','array']]);
        return request()->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email'=> 'unique:leads,email,'.request()->lead->id.'|required',
            'planned_date'=> 'date|required',
            'domain_name'=>'nullable',
            'path' => 'nullable',
            'client_ip_address' => 'nullable',
            'user_agent_string' => 'nullable',
        ]);
    }

    public function grantAccessToCompany(Lead $lead){
        $this->authorize('update',$lead);
        $company = Company::find(request()->company_id);
        $lead->grantAccessToCompany($company);
        return response('added',204);
    }

    public function revokeAccessFromCompany(Lead $lead, Company $company){
        $this->authorize('update',$lead);
        $lead->revokeAccessFromCompany($company);
        return response('removed',200);
    }
}



