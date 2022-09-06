<?php

namespace App\Models;

use App\RecordActivity;
use Illuminate\Database\Eloquent\Relations\Pivot;

class LeadAttribute extends Pivot
{
    use RecordActivity;

    public function lead(){
        return $this->belongsTo(Lead::class,'lead_id');
    }

    public function attribute(){
        return $this->belongsTo(Attribute::class,'attribute_id');
    }

    protected function getCompanyId(){
        return $this->lead->company->id;
    }

}
