<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use App\Models\Role;
use App\Models\CompanyEmployeeRole;
use Tests\TestCase;

class ManageCompaniesTest extends TestCase
{   
    /** @test */
    public function an_authorized_user_can_update_a_company(){
        $user = User::factory()->create();
        $company = Company::factory()->create();
        $this->actingAs($user);
        $this->post($company->path(),$attributes = [
                'name'=>'Changed',
                'description'=>'Changed',
            ])
            ->assertStatus(403);
        $company->invite($user,Role::where('name','Owner')->first());
        $this->post($company->path(),$attributes = [
                'name'=>'Changed',
                'description'=>'Changed',
            ])
            ->assertStatus(204);  
        $this->assertDatabaseHas('companies', $attributes);
    }

    /** @test */
    public function an_authorized_user_can_delete_employee(){
        $user = User::factory()->create();
        $employee = User::factory()->create();
        $company = Company::factory()->create();
        $company->invite($employee);
        $this->actingAs($user);

        $this->delete($company->path()."/employees/".$employee->id)->assertStatus(403);
        $this->assertTrue((boolean)$company->employees()->get()->where("id",$employee->id)->count());
        
        $company->invite($user,Role::where('name','Owner')->first());
        $this->delete($company->path()."/employees/".$employee->id)->assertStatus(302);
        $this->assertFalse((boolean)$company->employees()->where("user_id",$employee->id)->count());

        $this->delete($company->path()."/employees/".$user->id)->assertStatus(403);
        $this->assertTrue((boolean)$company->employees()->get()->where("id",$user->id)->count());
    }

    /**  @test */
    public function an_authorized_user_can_change_roles(){
        $owner = User::factory()->create();
        $employee = User::factory()->create();
        $company = Company::factory()->create();

        $company->invite($owner,Role::where('name','Owner')->first());
        $company->invite($employee);
        $this->actingAs($employee);
        $this->post($company->path().'/employees/'.$owner->id,[
            'role_id'=>Role::where('name','Employee')->first()->id,
        ])
        ->assertStatus(403);
        $this->actingAs($owner);
        $this->post($company->path().'/employees/'.$employee->id,[
            'role_id'=>$ownerRoleId=Role::where('name','Owner')->first()->id,
            ])
            ->assertStatus(204);
        $attributes=[
            'company_id'=>$company->id,
            'user_id'=>$employee->id,
            'role_id'=>$ownerRoleId
        ];
        $this->assertDatabaseHas('company_employee_role', $attributes);
    }
}
