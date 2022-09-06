<?php
namespace Database\Seeders\Setup;

use App\Models\Status;

class StatusFactory{
    public function createStatuses(){
        Status::factory()->create([
            'name'=>'New'
        ]);
        Status::factory()->create([
            'name'=>'Follow up (positive)'
        ]);
        Status::factory()->create([
            'name'=>'Follow up (negative)'
        ]);
        Status::factory()->create([
            'name'=>'Cancelled'
        ]);
    }
}