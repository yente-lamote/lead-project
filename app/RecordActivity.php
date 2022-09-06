<?php

namespace App;

use App\Models\Activity;
use Illuminate\Support\Arr;

trait RecordActivity{
    public $note=null;
    public $wasCreated=false;
    public $removedEmployee=null;
    public $extraCompany=null;
    public function setNote($note)
    {
        return $this->note = $note;
    }
    public function setWasCreated($wasCreated)
    {
        return $this->wasCreated = $wasCreated;
    }

    public function setRemovedEmployee($removedEmployee)
    {
        return $this->removedEmployee = $removedEmployee;
    }
    public function setExtraCompanyForActivity($extraCompany)
    {
        return $this->extraCompany = $extraCompany;
    }

    public static function bootRecordActivity(){
        foreach(self::recordableEvents() as $event){
            static::$event(function($model) use($event){
                $model->recordActivity("{$event}_". strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', class_basename($model))));
            });
        }
    }
    protected static function recordableEvents(){
        if(isset(static::$recordableEvents)){
            return static::$recordableEvents;
        }else{
            return ['updated'];
        }
    }

    public function activities(){
        return $this->morphMany(Activity::class, 'subject')->latest();
    }

    public function recordActivity($description){
        if(auth()->user()){
            $this->activities()->create([
                'user_id' => auth()->user()->id,
                'description' => $description,
                'changes' => $this->activityChanges(),
                'note'=>$this->note, //note is value dat op een model geplaats kan worden maar moet niet
                'company_id' => $this->getCompanyId()??($this->company??$this)->id
            ]);
        }
    }

    private function activityChanges()
    {
        $changedKeys = collect($this->getChanges())->keys();
        if ($this->wasChanged()) {
            return [
                'before' => Arr::except(
                    array_intersect_key($this->getOriginal(), array_flip($changedKeys->toArray()))
                    , 'updated_at'
                ),
                'after' => Arr::except(
                    $this->getChanges(), 'updated_at'
                )
            ];
        }
        if($this->wasCreated){
            return [
                'before' => [],
                'after' => Arr::except(
                    $this->getAttributes(), 'updated_at'
                )
            ];
        }
        if($this->removedEmployee){
            return Arr::except(
                $this->removedEmployee->getAttributes(), ['password','updated_at','remember_token','email_verified_at']
            );
        }
        if($this->extraCompany){
            return Arr::except(
                $this->extraCompany->getAttributes(), ['updated_at','created_at',]
            );
        }
    }

    //overrideable
    private function getCompanyId(){
        return null;
    }
}