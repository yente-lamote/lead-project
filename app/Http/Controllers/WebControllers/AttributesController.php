<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Company;
use App\Models\Lead;
use Illuminate\Http\Request;

class AttributesController extends Controller
{
    public function store(Lead $lead){
        $this->authorize('update',$lead);
        $attribute = Attribute::firstOrCreate($this->validateAttribute());
        $lead->attributes()->sync([$attribute->id=>$this->validateValue()],false);
        //recordactivity moet hier gebeuren bij lead_attribute pivot want bij created event wordt subject_id niet opgeslaan in activity
        $leadAttribute=$lead->attributes()->latest()->first()->pivot;
        $leadAttribute->setWasCreated(true);
        $leadAttribute->recordActivity("created_lead_attribute");
        return response('created',204);
    }

    protected function validateAttribute(){
        return request()->validate([
            'name' => 'string|required',
        ]);
    }
    protected function validateValue(){
        return request()->validate([
            'value' => 'string',
        ]);
    }
}
