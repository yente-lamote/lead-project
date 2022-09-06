<?php

namespace App\Http\Controllers\ApiControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Company;

class CompaniesController extends Controller
{
    //
    /**
     * @OA\Get(
     *      path="/companies",
     *      operationId="getCompanies",
     *      tags={"Companies"},
     *      summary="Get list of companies you work for",
     *      description="Returns list of companies you work for",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Company")
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *     )
     */
    public function index(){
        $companies = auth()->user()->companies()->paginate(10);
        return json_encode($companies);
    }

    /**
     * @OA\Get(
     *      path="/companies/all",
     *      operationId="getAllCompanies",
     *      tags={"Companies"},
     *      summary="Get list of all the companies with their name and id",
     *      description="Get list of all the companies with their name and id",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *                type="object",
     *                title="Company teaser model",
     *                required={"name"},
     *                @OA\Xml(
     *                    name="CompanyTeaser"
     *                ),
     *                @OA\Property(property="id",type="integer",title="ID",example="1", readOnly="true"),
     *                @OA\Property(property="name",type="string",title="Name",example="Company name"),
     *          )
     *       ),
     *     )
     */
    public function getAllCompanies(){
        $companies = Company::select('id','name')->get();
        return json_encode($companies);
    }
}
