<?php
namespace Tests\Setup;

use App\Models\Company;
use App\Models\Lead;

class LeadFactory{
    protected $company;
    public function ownedBy($company){
        $this->company=$company;
        return $this;
    }

    public function create(){
        $lead = Lead::factory()->create([
            'company_id'=> $this->company ?? Company::factory()
        ]);
        
        return $lead;
    }
}