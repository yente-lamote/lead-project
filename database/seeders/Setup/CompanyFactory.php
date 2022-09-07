<?php
namespace Database\Seeders\Setup;

use App\Models\Attribute;
use App\Models\Company;
use App\Models\Lead;
use App\Models\Role;
use App\Models\User;
use Faker\Factory;
use App\Models\Status;
use Illuminate\Support\Facades\Http;

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
        $statuses = Status::all();
        $users->each(function($user)use($company,$statuses){
            $leads = $company->accessibleLeads();
            $leads->each(function($lead)use($company, $user, $statuses){
                if(rand(0,7)==1){
                    $newStatusIndex=1;//index van follow up positive
                    if(rand(0,5)>1){
                        $newStatusIndex=rand(0,3);
                    }
                    $user->leads_changed=$user->leads_changed+1;
                    if($newStatusIndex==1){
                         $user->positive_leads=$user->positive_leads+1;
                    }
                    $user->update();
                    $prevStatus=$lead->status;
                    $lead->status_id=$statuses[$newStatusIndex]->id;
                    $lead->update();
                    $lead->activities()->create([
                        'user_id' => $user->id,
                        'description' => "updated_lead",
                        'changes' => [
                            'before' => ['status'=>$prevStatus->name],
                            'after' => ['status'=>$lead->status->name]
                        ],
                        'note'=>"Changed the status to ".$lead->status->name,
                        'company_id' => $company->id
                    ]);
                }
            });
        });
    }
}