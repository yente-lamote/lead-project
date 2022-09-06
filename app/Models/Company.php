<?php

namespace App\Models;

use App\RecordActivity;
use App\ScoutSearchable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 
 *
 *
 * @OA\Schema(
 *     type="object",
 *     title="Company model",
 *     required={"name"},
 *     @OA\Xml(
 *         name="Company"
 *     ),
 *     @OA\Property(property="id",type="integer",title="ID",example="1", readOnly="true"),
 *     @OA\Property(property="name",type="string",title="Name",example="Company name"),
 *     @OA\Property(property="description",type="string",title="Description",example="Company description"),
 *     @OA\Property(property="created_at",type="string",format="date",title="Created at", example="2020-07-21 16:00:01", readOnly="true"),
 *     @OA\Property(property="updated_at",type="string",format="date",title="Updated at", example="2020-07-22 12:20:41", readOnly="true"),
 *
 *)
 */


class Company extends Model
{
    use HasFactory, ScoutSearchable, RecordActivity;
    protected $guarded = [];
    protected $hidden = ['pivot'];

    public function employees(){
        return $this->belongsToMany(User::class,'company_employee_role')
            ->using(CompanyEmployeeRole::class)->withPivot('role_id')->withTimestamps();
    }

    public function ownedLeads(){
        return $this->hasMany(Lead::class,'company_id')->where('archived',false);
    }
    //dit is er alleen maar voor factories, om leads  op te halen gebruik je best ownedLeads of accessibleLeads om verwarring te vermijden
    public function leads(){
        return $this->hasMany(Lead::class,'company_id');
    }

    public function accessibleLeads(){
        return Lead::AccessibleByCompany($this->id);
        //return $this->belongsToMany(Lead::class,'company_leads');
    }

    //dit linkt op company_id van de tabel en niet aan subject_id zoals bij alle andere recorded activities
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
   
    public function invite(User $user, $role=null){
        if($role===null)$role = Role::where('name','Employee')->first();
        return $this->employees()->attach($user,['role_id'=>$role->id]);
    }

    public function remove(User $user){
        $this->setRemovedEmployee($user);
        $this->recordActivity("removed_employee");
        return $this->employees()->detach($user);
    }

    public function path(){
        return '/companies/'.$this->id;
    }

    public function countAccessibleLeads(){
        return count($this->accessibleLeads()->get());
    }
    public function countEmployees(){
        return count($this->employees);
    }

    public function getLeadsFromCertainDay($date){
        //return Company::find($this->id)->whereDate('created_at', '=', Carbon::today()->toDateString());
        return Lead::whereDate('created_at', $date->toDateString())
            ->AccessibleByCompany($this->id)
            ->paginate(5, ["*"], "new_leads_page");
    }

    public function getAmountOfNewLeadsPerDay($amountOfDaysBack){
        $collection = Lead::whereDate('created_at', '>=',Carbon::today()->subDays($amountOfDaysBack))
        ->AccessibleByCompany($this->id)
        ->selectRaw("COUNT(*) as amount, Date(created_at) as created_at")
        ->groupBy(DB::raw('Date(created_at)'))
        ->orderBy('created_at')
        ->get();
        return $this->addSkippedDays($collection,$amountOfDaysBack);
    }

    private function addSkippedDays($collectionOfDays,$amountOfDaysBack){
        for($i=0;$i<=$amountOfDaysBack;$i++){
            $day=Carbon::today()->subDays($i);
            $dayIsInCollection = 
                $collectionOfDays->filter(function($element) use($day){
                    return $day->isSameDay($element['created_at']);
                })->count();
            if(!$dayIsInCollection){
                $collectionOfDays->push(['amount'=>0,"created_at"=>$day]);
            }
        }
        return json_encode($collectionOfDays->sortBy('created_at')->values()->all());
    }


    public function getLeadsWhereStatusChanged($statusName= null,$userId=null,$date=null){
        $query=$this->activities()->where("subject_type","App\Models\Lead");
        if($userId){
            $query=$query->where('user_id',$userId);
        }
        if($date){
            $query=$query->whereDate('created_at', $date);
        }
        $activities=$query->get();
        //$activities=$this->activities()->where("subject_type","App\Models\Lead")->whereDate('created_at', Carbon::today())->get();
        $leads=[];
        foreach($activities as $activity){
            if(isset($activity->subject->id)){
                if($statusName){
                    if(isset($activity['changes']['after']['status'])){
                        if($activity['changes']['after']['status']===$statusName && 
                            $activity->subject->status->name===$statusName &&
                            !isset($leads[$activity->subject->id])){
                            $leads[$activity->subject->id]=$activity->subject;
                        }
                    }
                }else{
                    if(!isset($leads[$activity->subject->id])&&isset($activity['changes']['after']['status'])){
                        $leads[$activity->subject->id]=$activity->subject;
                    }
                }
            }
        }

        return array_values($leads);
    }
    
    public function employeeSuccessRate($employeeId){
        $amountPositiveLeads= count($this->getLeadsWhereStatusChanged('Follow up (positive)',$employeeId));
        $totalChangedLeads=count($this->getLeadsWhereStatusChanged(null,$employeeId));
        if($amountPositiveLeads===0||$totalChangedLeads===0){
            return 0;
        }
        return round($amountPositiveLeads/($totalChangedLeads)*100);
    }

    public function employeesSortedBy($property,$pageName){
        $mappedEmployees=$this->employees->map(function ($employee) use ($property){
            if($property==='successRate'){
                $employee['successRate']=$this->employeeSuccessRate($employee->id);
            }
            if($property==='totalSuccessful'){
                $employee['totalSuccessful']=count($this->getLeadsWhereStatusChanged('Follow up (positive)',$employee->id));
            }
            return $employee;
        });
        $sortDirection=request()->has($pageName.'_sort_direction')?request()->query($pageName.'_sort_direction'):'desc';
        if($sortDirection!=='desc'&&$sortDirection!=='asc'){
            $sortDirection='desc';
        }
        $sortedEmployees=$mappedEmployees->sortByDesc([
            [$property, $sortDirection]
        ]);
        return $this->paginate($sortedEmployees,5,$pageName.'_page');
    }
    public function paginate($collection, $perPage,$pageName)
    {
        $currentPage=request()->has($pageName)?request()->query($pageName):1;
        $paginated=new LengthAwarePaginator($collection->forPage($currentPage, $perPage), $collection->count(), $perPage, $currentPage);
        $paginated->setPageName($pageName);
        $paginated->appends(request()->input());
        return $paginated;

    }

    public function getLeadsByStatus($statusName){
        $status = Status::where('name',$statusName)->first();
        return $this->accessibleLeads()->where('status_id',$status->id)->get();
    }

    public function successRate(){
        $statusPositiveId = Status::where('name','Follow up (positive)')->first()->id;
        $amountPositiveLeads = count($this->accessibleLeads()->where('status_id',$statusPositiveId)->get());
        $statusNewId = Status::where('name','New')->first()->id;
        $amountNewLeads = count($this->accessibleLeads()->where('status_id',$statusNewId)->get());
        $totalAccessibleLeads=count($this->accessibleLeads()->get());
        if($amountPositiveLeads===0||$totalAccessibleLeads===0){
            return 0;
        }
        if($amountPositiveLeads===$totalAccessibleLeads){
            return 100;
        }
        return round($amountPositiveLeads/($totalAccessibleLeads-$amountNewLeads)*100);
    }

    public function successRateToday(){
        $changedToPositiveToday = count($this->getLeadsWhereStatusChanged('Follow up (positive)',null,Carbon::today()));
        $totalChanged = count($this->getLeadsWhereStatusChanged(null,null,Carbon::today()));
        if($changedToPositiveToday===0||$totalChanged===0){
            return 0;
        }else{
            return round($changedToPositiveToday/$totalChanged*100); 
        }
    }
}
