<?php

namespace Tests\Feature;

use App\Models\Attribute;
use App\Models\Company;
use App\Models\Lead;
use App\Models\User;
use Facades\Tests\Setup\LeadFactory;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;

class ManageLeadsTest extends TestCase
{
    /** @test */
    public function show_leads(){
        $lead = LeadFactory::create();
        $bob = User::factory()->create();
        $jimmy = User::factory()->create();
        $lead->company->invite($bob);
        $this->actingAs($bob)->get($lead->company->path().'/leads')
            ->assertSee($lead->fullname());
        $this->actingAs($jimmy)->get($lead->company->path().'/leads')
            ->assertStatus(403);
    }
    /** @test */
    public function show_specific_lead(){
        $lead = LeadFactory::create();
        $bob = User::factory()->create();
        $lead->company->invite($bob);
        $this->actingAs($bob)->get($lead->path())
            ->assertSee($lead->first_name);
    
        $company2=Company::factory()->create();
        $jimmy = User::factory()->create();
        $company2->invite($jimmy);
        $lead->grantAccessToCompany($company2);
        $this->actingAs($jimmy)->get($lead->path())
            ->assertSee($lead->first_name);
    }

    /** @test */
    public function add_lead(){
        $company = Company::factory()->create();
        $attributes = Lead::factory()->raw(['company_id'=>$company->id]);
        $attributes['planned_date']=$attributes['planned_date']->format('Y-m-d H:i:s');
        $attributes['extra_attributes']=json_encode((object)[$keyName="testKey"=>$value="testValue"]);
        unset($attributes['status_id']);
        $this->postJson('/api/v1/leads',$attributes)
            ->assertStatus(201);
        unset($attributes['first_name']);
        $this->postJson('/api/v1/leads',$attributes)
            ->assertStatus(422);  
        unset($attributes['created_at']);
        unset($attributes['extra_attributes']);
        $this->assertDatabaseHas('leads',$attributes);
        $this->assertDatabaseHas('attributes',['name'=>$keyName]);
        $this->assertDatabaseHas('lead_attribute',['value'=>$value]);
    }

    /** @test */
    public function a_user_can_archive_a_lead(){
        $lead = LeadFactory::create();
        $user = User::factory()->create();
        $this->signIn($user);
        $this->delete($lead->path())
            ->assertStatus(403);
        $this->assertDatabaseHas('leads', $lead->only('id'));
        $lead->company->invite($user);
        $this->delete($lead->path())
            ->assertStatus(200);
        $this->assertDatabaseHas('leads', [
            'id'=>$lead->id,
            'archived'=>true
        ]);
    }

    /** @test */
    public function an_authorized_user_can_update_a_lead(){
        $company = Company::factory()->create();
        $lead = Lead::factory()->create(['company_id'=>$company->id]);
        $user = User::factory()->create();
        $this->actingAs($user);
        $attributes=$lead->updateableDefaultAttributes();
        $attributes['first_name']='new name';
        $attributes['planned_date']=$attributes['planned_date']->format('Y-m-d H:i:s');
        $this->post($lead->path(),$attributes)
            ->assertStatus(403);
        $company->invite($user);
        $this->post($lead->path(),$attributes)
            ->assertStatus(204);  
        $this->assertDatabaseHas('leads', ['first_name'=>$attributes['first_name']]);
    }

    /** @test */
    public function an_authorized_user_can_add_attribute_to_a_lead(){
        $company = Company::factory()->create();
        $lead = Lead::factory()->create(['company_id'=>$company->id]);
        $user = User::factory()->create();
        $this->actingAs($user);
        $this->post($lead->path()."/attributes",$attributes=['name'=>'attributeName','value'=>'132'])
            ->assertStatus(403); 
        $company->invite($user);
        $this->post($lead->path()."/attributes",$attributes)
            ->assertStatus(204);  
        unset($attributes['value']);
        $this->assertDatabaseHas('attributes', $attributes);
    }

}
