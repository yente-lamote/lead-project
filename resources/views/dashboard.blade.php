@extends('layouts.app')
@section('content')
    <header class="md:flex justify-between items-center pb-5">
        <div class="mb-2 md:mb-0">
            <h1 class="text-3xl text-gray-600">Dashboard</h1>
            <span class="text-gray-400 text-md">{{$company->name}}</span>
        </div>
        <div class="md:flex items-center">
            <a href="{{$company->path()}}" class="button-primary inline-block mr-4 mb-2 md:mb-0">View company</a>
            <select-company initial-companies="{{$companies}}" page="dashboard" initial-selected-company="{{$company->id}}"></select-company>
        </div>
    </header>
    <div class="column-container">
        <div class="column 2xl:mr-3">
            <div class="card shadow px-2 pb-4 pt-2">
                @php
                    $data=[];
                    $labels=[];
                    foreach(App\Models\Status::all() as $status){
                        array_push ( $labels , $status->name );
                        array_push( $data,
                            count($company->getLeadsByStatus($status->name))
                        );
                    }
                @endphp
                <leads-pie-chart
                    :initial-labels="{{json_encode($labels)}}"
                    :initial-data="{{json_encode($data)}}"
                ></leads-pie-chart>
            </div>
            <div class="column-container md:flex w-full mt-4">
                <div class="card mb-4 md:mr-2 column pb-4">
                    <header class="p-4 border-b border-gray-300 mb-2 mr-0.5 flex items-center">
                        <h3 class="font-bold text-sm">Employees by successful leads</h3>
                        <sort-by page="employee_successful"></sort-by>
                    </header>
                    @foreach($company->employeesSortedBy('totalSuccessful','employee_successful') as $employee)
                        <div class="px-6 py-2 flex items-center justify-between">
                            <span class="text-sm text-gray-500">{{$employee->name}}</span>
                            <span class="text-sm font-medium">{{$employee->totalSuccessful}}</span>
                        </div>
                    @endforeach
                    <div class="px-4 mt-3">
                    {{$company->employeesSortedBy('totalSuccessful','employee_successful')->links('vendor.pagination.simple-tailwind')}}
                    </div>
                </div>
                <div class="card md:ml-2 mb-4 column pb-4">
                    <header class="px-6 py-4 border-b border-gray-300 mb-2 mr-0.5 flex items-center">
                        <h3 class="font-bold text-sm">Employees by success rate</h3>
                        <sort-by page="employee_ratio"></sort-by>
                    </header>
                    @foreach($company->employeesSortedBy('successRate','employee_ratio') as $employee)
                        <div class="px-6 py-2 flex items-center justify-between">
                            <span class="text-sm text-gray-500">{{$employee->name}}</span>
                            <span class="text-sm font-medium">{{$employee->successRate}}%</span>
                        </div>
                    @endforeach
                    <div class="px-4 mt-3">
                        {{$company->employeesSortedBy('successRate','employee_ratio')->links('vendor.pagination.simple-tailwind')}}
                    </div>
                </div>
            </div>
        </div>
        <div class="column xl:ml-3">
                <div class="card p-4 flex">
                    <div class="w-1/3 z-10">
                        <div class="relative">
                            <div class="absolute w-full h-full" style="z-index:-1;">
                                <div class="flex justify-center items-center h-full">
                                    <div class="text-center -mt-2">
                                        <span class=" text-md md:font-bold md:text-lg">
                                        {{count($company->getLeadsByStatus('Follow up (positive)'))}}
                                        </span>
                                        <p class="text-xs font-extralight md:text-sm">Positive leads</p>
                                    </div>
                                </div>
                            </div>
                            <success-rate-chart chart-id="totalSuccessRate" :enable-tooltips='true' :success-rate="{{$company->successRate()}}"></success-rate-chart>
                        </div>
                        <div class="hidden md:block mt-2">
                            <div class="flex justify-center items-center">
                                <div class="bg-blue-600 w-4 h-2 mt-0.5"></div>
                                <span class="ml-2 text-gray-400 text-sm">Positive leads</span>
                            </div>
                            <div class="flex justify-center items-center">
                                <div class="bg-blue-600 opacity-20 w-4 h-2 mt-0.5"></div>
                                <span class="ml-2 text-gray-400 text-sm">All other leads excl. New</span>
                            </div>
                        </div>
                    </div>
                    <div class="md:mx-8 mx-3 md:mt-4 w-full flex-1">
                        <span class="text-lg font-bold">{{$company->countAccessibleLeads()}} <span class="text-gray-400 text-base font-normal">Total leads</span></span>
                        <div class="mt-2 pt-4 border-t border-gray-300 grid grid-cols-2 gap-5">
                            <div>
                                <p class="text-md font-medium">{{$company->successRate()}}%</p>
                                <p class="text-sm text-gray-400">Success rate</p>
                            </div>
                            <div>
                                <p class="text-md font-medium">{{count($company->getLeadsByStatus('new'))}}</p>
                                <p class="text-sm text-gray-400">New leads</p>
                            </div>
                        </div>
                    </div>
                </div>
            <h3 class="mt-2 mb-1 text-gray-600 text-xl">Today's statistics</h3>
            <div class="small-card-container">
                <div class="card">
                    <div class="left">
                        @include('icons.plus')
                    </div>
                    <div class="right">
                        <p>{{json_decode($company->getAmountOfNewLeadsPerDay(0))[0]->amount}}</p>
                        <p>Leads added</p>
                    </div>
                </div>
                <div class="card">
                    <div class="left">
                        @include('icons.pencil')
                    </div>
                    <div class="right">
                        <p>{{count($company->getLeadsWhereStatusChanged(null,null,\Carbon\Carbon::today()))}}</p>
                        <p>Leads changed</p>
                    </div>
                </div>
                <div class="card">
                    <div class="flex items-center w-1/6">
                        <success-rate-chart chart-id="successRateToday" :enable-tooltips='false' :success-rate="{{$company->successRateToday()}}"></success-rate-chart>
                    </div>
                    <div class="right">
                        <p>{{$company->successRateToday()}}%</p>
                        <p>Success rate</p>
                    </div>     
                </div>
                <div class="card">
                    <div class="left">
                        @include('icons.check')
                    </div>
                    <div class="right">
                        <p>{{count($company->getLeadsWhereStatusChanged('Follow up (positive)',null,\Carbon\Carbon::today()))}}</p>
                        <p>Successful</p>
                    </div>
                </div>
                <div class="card">
                    <div class="left">
                        @include('icons.close')
                    </div>       
                    <div class="right">             
                        <p>{{count($company->getLeadsWhereStatusChanged('Cancelled',null,\Carbon\Carbon::today()))}}</p>
                        <p>Cancelled</p>
                    </div>
                </div>
                <div class="card">
                    <div class="left">
                        @include('icons.minus')
                    </div>
                    <div class="right">
                        <p>{{count($company->getLeadsWhereStatusChanged('Follow up (negative)',null,\Carbon\Carbon::today()))}}</p>
                        <p>Negative follow up</p>
                    </div>
                </div>        
            </div>
        </div>
    </div>
@endsection
