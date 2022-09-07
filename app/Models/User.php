<?php

namespace App\Models;

use App\ScoutSearchable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, ScoutSearchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'leads_changed',
        'positive_leads'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function companies(){
        return $this->belongsToMany(Company::class,'company_employee_role')->using(CompanyEmployeeRole::class)->withPivot('role_id')->withTimestamps();;
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function companyIds(){
        return array_column($this->companies->toArray(), 'id');
        /*return array_map(function($company){
            return $company['id'];
        },$this->companies->toArray());*/
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    } 
    
    public function path(){
        return '/users/'.$this->id;
    }

    public function getRole($company){
        $role_id=CompanyEmployeeRole::where('user_id',$this->id)->where('company_id',$company->id)->first()->role_id;
        return Role::find($role_id);
    }

    public function successRate($company_id){
        $activities=$this->activities()->where("subject_type","App\Models\Lead")->where('company_id', $company_id)->get();
    }

    public function getLeadsWhereEmployeeChangedStatus($company_id, $statusName=null){
        $activities=$this->activities()->where("subject_type","App\Models\Lead")->where('company_id', $company_id)->get();
        $leads=[];
        foreach($activities as $activity){
            if($statusName){
                if(isset($activity['changes']['after']['status'])){
                    if($activity['changes']['after']['status']===$statusName && 
                        $activity->subject->status->name===$statusName &&
                        !isset($leads[$activity->subject->id])){
                        $leads[$activity->subject->id]=$activity->subject;
                    }
                }
            }else{
                if(!isset($leads[$activity->subject->id])){
                    $leads[$activity->subject->id]=$activity->subject;
                }
            }
        }
        return array_values($leads);
    }
}
