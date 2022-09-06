<?php

namespace App\Http\Controllers\WebControllers;

use App\Http\Controllers\Controller;
use MeiliSearch\Endpoints\Indexes;

class SearchController extends Controller
{
    //
    public function index(){
        if(auth()->user()){
            $leads = $this->search("Lead","companies","leads_page");
            $companies = $this->search("Company","id","companies_page");
            return view('search.index',compact('leads','companies'));
        }
    }

    public function filteredSearch($modelPlurar){
        $filter="";
        $modelName="";
        $pageName="";
        switch($modelPlurar){
            case "companies":
                $modelName="Company";
                $filter="id";
                $pageName="companies_page";
                break;
            case "leads":
                $modelName="Lead";
                $filter="companies";
                $pageName="leads_page";
                break;
            default:
                abort(404);
        };
        $result=$this->search($modelName,$filter,$pageName);
        return view('search.index')->with($modelPlurar,$result);
    }

    private function search($model,$filter,$pageName){
        $model="App\Models\\".$model;
        return $model::search(request()->query('query'),function (Indexes $meilisearch, $query, $options) use($filter) {
            $options['filters'] = $this->makeFilter($filter);
            return $meilisearch->search($query, $options);
        })->paginate(6,$pageName);
    }

    private function makeFilter($propertyToFilterOn){
        $filter='';
        for($i=0;$i< count($companyIds=auth()->user()->companyIds());$i++){
            $filter.=$propertyToFilterOn.' = '.$companyIds[$i];
            if($i<count($companyIds)-1){
                $filter.=' OR ';
            }
        }
        return $filter;
    }
}
