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
        $mainUser = User::factory()->create([
            'email'=>'test@test.be',
            'password'=>'$2y$12$X0lz/JtphJtE9xsWkFcSSeqLGBykfqMwMZpJC3NHBfI40yBgyNzWe'//test
        ]);
        $firstCompany = CompanyFactory::ownedBy($mainUser)->withLeads(50)->withExtraLeadAttributes(4)->withEmployees(15)->create();
        CompanyFactory::ownedBy($mainUser)->withLeads(100)->withEmployees(4)->create();
        CompanyFactory::ownedBy($mainUser)->withLeads(20)->withExtraLeadAttributes(15)->withEmployees(8)->companyThatCanViewLeads($firstCompany)->create();
    }
}
