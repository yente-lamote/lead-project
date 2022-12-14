<?php

namespace Database\Seeders;

use App\Models\User;
use Facades\Database\Seeders\Setup\RoleFactory;
use Facades\Database\Seeders\Setup\StatusFactory;
use Illuminate\Database\Seeder;
use Facades\Database\Seeders\Setup\CompanyFactory;

class DatabaseSeeder extends Seeder
{


    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {   
        RoleFactory::createRoles();
        StatusFactory::createStatuses();
        $userWithNoRights = User::factory()->create([
            'email'=>'john.doe@gmail.com',
            'password'=>'$2y$12$X0lz/JtphJtE9xsWkFcSSeqLGBykfqMwMZpJC3NHBfI40yBgyNzWe'//test
        ]);
        $mainUser = User::factory()->create([
            'email'=>'john.doe@company.com',//was test@test.be
            'password'=>'$2y$12$X0lz/JtphJtE9xsWkFcSSeqLGBykfqMwMZpJC3NHBfI40yBgyNzWe'//test
        ]);
        $firstCompany = CompanyFactory::ownedBy($mainUser)->withLeads(50)->withExtraLeadAttributes(2)->withEmployees(4)->create();
        $secondCompany = CompanyFactory::ownedBy($mainUser)->withLeads(100)->withEmployees(4)->create();
        $thirdCompany = CompanyFactory::ownedBy($mainUser)->withLeads(80)->withExtraLeadAttributes(5)->withEmployees(8)->companyThatCanViewLeads($firstCompany)->create();
        
        $firstCompany->invite($userWithNoRights);
        $secondCompany->invite($userWithNoRights);
        $thirdCompany->invite($userWithNoRights);
    }
}
