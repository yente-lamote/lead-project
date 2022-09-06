<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\LeadCollection;
use App\Http\Resources\LeadResource;
use App\Models\Attribute;
use App\Models\Company;
use App\Models\Lead;
use App\Models\Status;
use App\Models\User;
use App\Rules\JSONEncodedObject;
use Illuminate\Database\Console\Migrations\StatusCommand;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LeadsController extends Controller
{
    //
    /**
     * @OA\Get(
     *      path="/companies/{companyId}/leads",
     *      operationId="getLeads",
     *      tags={"Leads"},
     *      summary="Get list of leads",
     *      description="Returns list of leads",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Lead")
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not found"
     *      ),
     *     )
     */
    public function index(Company $company){
        $this->authorize('view',$company);
        return json_encode($company->accessibleLeads()->with('companies')->paginate(20));
    }

    /**
     * @OA\Post(
     *      path="/leads",
     *      operationId="storeLead",
     *      tags={"Leads"},
     *      summary="Store new lead",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Lead")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Created",
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity"
     *      ),
     * )
     */

    public function store(){
        $lead = Lead::create($this->validateRequest());
        if(request()->extra_attributes){
            $extraAttributes=json_decode(request()->extra_attributes);
            foreach ($extraAttributes as $key=>$value) {
                $attribute = Attribute::firstOrCreate([
                    'name' => $key
                ]);
                $lead->attributes()->sync([$attribute->id=>['value' => $value]],false);
            }
        }
        return response('created',201);
    }

    protected function validateRequest(){
        request()->validate(['extra_attributes'=>['sometimes','json', new JSONEncodedObject]]);
        $validation = request()->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email'=> 'required',
            'planned_date'=> 'date|required',
            'company_id'=>'exists:companies,id|required',
            'domain_name'=>'sometimes|nullable',
            'path' => 'sometimes|nullable',
            'client_ip_address' => 'sometimes|nullable',
            'user_agent_string' => 'sometimes|nullable',
        ]);
        $validation['status_id']=Status::where('name','New')->first()->id;
        return $validation;
    }
}
