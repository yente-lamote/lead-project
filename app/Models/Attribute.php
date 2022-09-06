<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;

    public function leads(){
        return $this->belongsToMany(Lead::class,'lead_attribute')->withTimestamps();;    
    }
}
