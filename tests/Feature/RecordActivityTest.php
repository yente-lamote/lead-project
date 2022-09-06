<?php

namespace Tests\Feature;

use Facades\Tests\Setup\LeadFactory;
use App\Models\Status;
use App\Models\User;
use App\Models\Role;
use App\Models\Company;
use App\Models\Lead;
use App\Models\Attribute;
use Tests\TestCase;

class RecordActivityTest extends TestCase
{
    /** @test */
    public function updating_a_lead_records_activity()
    {
        $this->withoutExceptionHandling();
        $lead = LeadFactory::create();
        $user = User::factory()->create();
        $this->actingAs($user);
        $originalStatus = $lead->status;
        $newStatus=Status::whereNotIn('id',[$originalStatus->id])->first();
        $lead->update(['status_id'=>$newStatus->id]);
        $this->assertEquals('updated_lead',$lead->activities->last()->description);
        $expected = [
            'before' => ['status' => $originalStatus->name],
            'after' => ['status' => $newStatus->name]
        ];
        $this->assertEquals($expected, $lead->activities->last()->changes);
    }

    /** @test */
    public function updating_a_company_records_activity()
    {
        $company = Company::factory()->create();
        $this->actingAs(User::factory()->create());
        $oldDescription = $company->description;
        $company->update(['description'=>'Changed']);
        $this->assertEquals('updated_company',$company->activities->last()->description);
        $expected = [
            'before' => ['description' => $oldDescription],
            'after' => ['description' => 'Changed']
        ];
        $this->assertEquals($expected, $company->activities->last()->changes);
    }

    /** @test */
    public function changing_employee_role_records_activity()
    {
        $owner = User::factory()->create();
        $employee = User::factory()->create();
        $company = Company::factory()->create();
        $company->invite($owner,Role::where('name','Owner')->first());
        $company->invite($employee);
        $this->actingAs($owner);
        $this->post($company->path().'/employees/'.$employee->id,[
            'role_id'=>$ownerRoleId=Role::where('name','Owner')->first()->id,
            ])
            ->assertStatus(204);
        $this->assertEquals('updated_company_employee_role',$company->activities->last()->description);
        $expected = [
            'before' => ['role_id' => Role::where('name','Employee')->first()->id],
            'after' => ['role_id' => $ownerRoleId]
        ];
        $this->assertEquals($expected, $company->activities->last()->changes);
    }

    /** @test */
    public function removing_employee_records_activity()
    {
        $owner = User::factory()->create();
        $employee = User::factory()->create();
        $company = Company::factory()->create();
        $company->invite($owner,Role::where('name','Owner')->first());
        $company->invite($employee);
        $this->actingAs($owner);
        $this->delete($company->path()."/employees/".$employee->id)->assertStatus(302);
        $this->assertEquals('removed_employee',$company->activities->last()->description);
    }
     /** @test */
     public function adding_attribute_to_lead_records_activity()
     {
        $lead=Lead::first();
        $user = User::factory()->create();
        $this->actingAs($user);
        $lead->company->invite($user);
        $this->post($lead->path()."/attributes",['name'=>'attributeName','value'=>'132'])
            ->assertStatus(204); 
        $this->assertEquals('created_lead_attribute',$lead->allActivities()->last()->description);
     }


}
