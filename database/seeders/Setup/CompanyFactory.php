<?php
namespace Database\Seeders\Setup;

use App\Models\Attribute;
use App\Models\Company;
use App\Models\Lead;
use App\Models\Role;
use App\Models\User;
use Faker\Factory;

class CompanyFactory{
    protected $owner;
    protected $amountEmployees=0;
    protected $amountLeads=0;
    protected $companyThatCanViewLeads;
    protected $amountExtraLeadAttributes=0;

    public function ownedBy($user){
        $this->owner=$user;
        return $this;
    }

    public function withEmployees($amount){
        $this->amountEmployees=$amount;
        return $this;
    }

    public function withLeads($amount){
        $this->amountLeads=$amount;
        return $this;
    }

    public function withExtraLeadAttributes($amount){
        $this->amountExtraLeadAttributes=$amount;
        return $this;
    }

    public function companyThatCanViewLeads($company){
        $this->companyThatCanViewLeads=$company;
        return $this;
    }

    public function create(){
        $faker = Factory::create();
        $company =  Company::factory()
            ->has(
                Lead::factory()->hasAttached(
                    Attribute::factory()->count($this->amountExtraLeadAttributes),
                    ['value'=>$faker->name()]
                )->count($this->amountLeads)
            )->create();

            $company->ownedLeads()->each(function($lead){
            if($this->companyThatCanViewLeads){
                $lead->grantAccessToCompany($this->companyThatCanViewLeads);
            }
        });

        if($this->owner) $company->invite($this->owner,Role::where('name','Owner')->first());
        $this->createEmployees($company);
        return $company;
    }

    private function createEmployees($company){
        $users=User::factory($this->amountEmployees)->create([
            'password'=>'$2y$12$X0lz/JtphJtE9xsWkFcSSeqLGBykfqMwMZpJC3NHBfI40yBgyNzWe'//test
        ]);
        $users->each(function($user)use($company){
            $company->invite($user);
        });
    }
}