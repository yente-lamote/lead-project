<?php

namespace Tests\Unit;

use App\Models\Lead;
use App\Models\User;
use App\Models\Company;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Facades\Tests\Setup\LeadFactory;
use Tests\TestCase;

class LeadTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function it_has_a_path()
    {
        $lead = LeadFactory::create();
        $this->assertEquals('/leads/' . $lead->id,  $lead -> path());
    }

    /** @test */
    public function it_has_full_name(){
        $lead = LeadFactory::create();
        $this->assertEquals($lead->first_name." ".$lead->last_name,  $lead -> fullName());
    }

    /** @test */
    public function it_can_get_all_related_activities(){
        $lead=Lead::factory()->create(['status_id'=>1,'company_id'=> Company::factory()->create()->id]);
        $user = User::factory()->create();
        $this->actingAs($user);
        $lead->update(['status_id'=>'2']);
        $lead->company->invite($user);
        $this->post($lead->path()."/attributes",['name'=>'attributeName','value'=>'132'])
            ->assertStatus(204); 
        $this->assertEquals(2,count($lead->allActivities()));
    }
}
