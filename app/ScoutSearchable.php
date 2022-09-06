<?php

namespace App;
use Laravel\Scout\Searchable;

trait ScoutSearchable{

    use Searchable;
    public function toSearchableArray()
    {
        $array = $this->toArray();
        $removeKeys = array('updated_at', 'created_at', 'password', 'email_verified_at');
        foreach($removeKeys as $key) {
            unset($array[$key]);
        }
        
        if(get_called_class()==="App\Models\Lead"){
            $array['companies']=array_map(function($company){
                return $company['id'];
            },
            $this->companies->toArray());
            array_push ($array['companies'], $this->company_id );
        }
        return $array;
    }
    
}