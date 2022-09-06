<?php
namespace Database\Seeders\Setup;

use App\Models\Role;

class RoleFactory{
    public function createRoles(){
        Role::factory()->create([
            'name'=>'Owner'
        ]);
        Role::factory()->create([
            'name'=>'Employee'
        ]);
        Role::factory()->count(5)->create();
    }
}