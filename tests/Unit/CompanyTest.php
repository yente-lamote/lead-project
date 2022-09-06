<?php

namespace Tests\Unit;

use App\Models\Company;
use App\Models\User;
use Carbon\Carbon;
use Facades\Tests\Setup\LeadFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Setup\LeadFactory as SetupLeadFactory;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_path()
    {
        $company = Company::factory()->create();
        $this->assertEquals('/companies/'.$company->id,$company->path());
    }
    
    /** @test */
    public function it_can_add_employee(){
        $company = Company::factory()->create();
        $company->invite($user=User::factory()->create());
        $this->assertTrue($company->employees->contains($user));
    }

    /** @test */
    public function it_can_count_employees(){
        $company = Company::factory()->create();
        $company->invite($user=User::factory()->create());
        $this->assertEquals(1,$company->countEmployees());
    }

    /** @test */
    public function it_can_count_accessible_leads(){
        $company = Company::factory()->create();
        LeadFactory::ownedBy($company)->create();
        $this->assertEquals(1,$company->countAccessibleLeads());
    }
}
