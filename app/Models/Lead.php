<?php

namespace App\Models;

use App\RecordActivity;
use App\ScoutSearchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 *
 * @OA\Schema(
 *     type="object",
 *     title="Lead model",
 *     required={"email", "first_name","last_name","planned_date","company_id"},
 *     @OA\Xml(
 *         name="Lead"
 *     ),
 *     @OA\Property(property="id",type="integer",title="ID",example="1", readOnly="true"),
 *     @OA\Property(property="email",type="string",format="email",title="Email",example="john.doe@gmail.com"),
 *     @OA\Property(property="first_name",type="string",title="First name",example="John"),
 *     @OA\Property(property="last_name",type="string",title="Last name",example="Doe"),
 *     @OA\Property(property="company_id",type="integer",description="Company that owns the lead",title="Company ID", example="1"),
 *     @OA\Property(property="planned_date",type="string",format="date",title="Planned date", example="2021-07-21"),
 *     @OA\Property(property="domain_name",type="string",title="Domain name",example="lead-project.test"),
 *     @OA\Property(property="path",type="string",title="Path",example="/leads/add"),
 *     @OA\Property(property="client_ip_address",type="string",title="Client ip address",example="127.0.0.1"),
 *     @OA\Property(property="user_agent_string",type="string",title="User agent string",example=""),
 *     @OA\Property(property="extra_attributes",type="string",format="json",title="Extra attributes", description="JSON string with an encoded object",nullable=true ,example="JSON string with an encoded object", writeOnly="true"),
 *     @OA\Property(property="created_at",type="string",format="date",title="Created at", example="2020-07-21 16:00:01", readOnly="true"),
 *     @OA\Property(property="updated_at",type="string",format="date",title="Updated at", example="2020-07-22 12:20:41", readOnly="true"),
 *
 *      @OA\Property(
 *          property="companies",
 *          title="Companies",
 *          description="All companies that can view this lead.",
 *          type="array",
 *          readOnly="true",
 *          @OA\Items(
 *              type="object",
 *              @OA\Property(property="id",type="integer",title="ID",example="1", readOnly="true"),
 *              @OA\Property(property="name",type="string",title="Name",example="Company name"),
 *              @OA\Property(property="created_at",type="string",format="date",title="Created at", example="2020-07-21 16:00:01", readOnly="true"),
 *              @OA\Property(property="updated_at",type="string",format="date",title="Updated at", example="2020-07-22 12:20:41", readOnly="true"), 
 *         )
 *     )
 * )
 */


class Lead extends Model
{
    use HasFactory, ScoutSearchable, RecordActivity;

    protected $guarded = [];
    protected $casts=[
        'planned_date'=>'datetime',
    ];

    protected static $recordableEvents=['deleted','updated'];

    //company that created the lead
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    //companies that can see the lead
    public function companies(){
        return $this->belongsToMany(Company::class,'company_leads')->withTimestamps();
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
    public function activities(){
        return $this->morphMany(Activity::class, 'subject')->latest();
    }
    public function allActivities(){
      return $this->activities()->union(Activity::where('subject_type','App\Models\LeadAttribute')->
            whereIn('subject_id',LeadAttribute::select('attribute_id')->where('lead_id',$this->id)->get()))->latest()
            ->paginate(10)->appends(request()->except('page'));
    }

    public function attributes(){
        return $this->belongsToMany(Attribute::class,'lead_attribute')->withPivot('id','value')->using(LeadAttribute::class)->withTimestamps();;

    }

    public function fullName(){
        return $this->first_name ." ". $this->last_name;
    }
    
    public function path(){
        return "/leads/".$this->id;
    }

    public function grantAccessToCompany(Company $company){
        $exists = $this->companies()->where('id',$company->id)->first();
        if($company->id!==$this->company->id && !$exists){
            $this->companies()->syncWithoutDetaching($company);
            $this->setExtraCompanyForActivity($company);
            $this->recordActivity("granted_access_to_company");
            $this->searchable();
        }
    }

    public function revokeAccessFromCompany(Company $company){
        $this->companies()->detach($company);
        $this->setExtraCompanyForActivity($company);
        $this->recordActivity("revoked_access_from_company");
        $this->searchable();
    }

    public function scopeAccessibleByCompany($query,$companyId)
    {
        return $query->where('company_id',$companyId)->where('archived',false)
            ->orWhereHas('companies',function($query) use($companyId){
                $query->where('company_id',$companyId);
            });
    }

    public function updateableDefaultAttributes(){
        return $this->only(['first_name', 'last_name','email','planned_date','domain_name',
            'path','client_ip_address','user_agent_string']);
    }
}
