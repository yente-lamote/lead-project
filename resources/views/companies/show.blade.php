@extends ('layouts.app')
@section('content')
<header class="mb-4 2xl:flex 2xl:items-center">
    <div class="flex justify-between items-center mr-4 flex-1">
        <h1 class="text-3xl text-gray-400 inline">Overview</h1>
        <button @click.prevent="$modal.show('edit-company')" class="button-not-filled">Edit</button>
    </div>
    <div class="2xl:ml-4 2xl:flex-1 mt-4 mb-2">
        <div class="2xl:float-right">
            <a href="{{$company->path()}}/activity-log" class="py-1 button-primary mr-2">Activity log</a>
            <a href="{{$company->path()}}/leads" class="py-1 button-primary">View all Leads</a>
        </div>
     </div>
</header>
<div class="column-container">
    <div class="column 2xl:mr-4">
        <div class="company-info py-8 px-2 rounded shadow bg-card flex">
            <div class="w-3/12 mx-6">
                <div class="rounded-image-container w-full inline-block align-top">
                    @if(file_exists('assets/images/companies/'.$company->id.'.png')) 
                        <img class="rounded-full shadow" src="{{ URL::to('/assets/images/companies/'.$company->id.'.png') }}" alt="company logo">
                    @else
                        <img class="rounded-full shadow" src="{{ URL::to('/assets/images/companies/default.png') }}" alt="company logo">
                    @endif
                </div>
            </div>
            <div class="pr-6 flex-1">
                <p class="text-xl mb-1">
                    {{$company->name}}
                </p>
                <p class="text-md text-gray-400">
                    {{$company->description}}
                </p>
            </div>
        </div>
        <div class="newest-leads">
            <header class="showCompanySubHeader flex justify-between items-center">
                <h2 class="subtitle">
                    Leads added today
                </h2>
                <select-action-button url-prefix="/leads" url-suffix="" button-text="Archive"></select-action-button>
            </header>
            <div class="bg-card rounded shadow px-6 py-1">
                @forelse($company->getLeadsFromCertainDay(\Carbon\Carbon::today()) as $lead)
                @php
                    //zodat de status als object weergegeven wordt in lead variable en niet alleen status id
                    $lead->status;
                @endphp
                    <lead-teaser :lead="{{$lead}}" path="{{$lead->path()}}" fullname="{{$lead->fullname()}}" last="{{$loop->last}}"></lead-teaser>
                @empty
                    <p class="mt-4 mb-3">No new leads were added today. Click <a href="{{$company->path()}}/leads" class="underline text-blue-600 hover:text-blue-400">here</a> to view all leads.</p>
                @endforelse
                @if($company->getLeadsFromCertainDay(\Carbon\Carbon::today())->hasPages())
                    <div class="mt-3 mb-4">
                        {{$company->getLeadsFromCertainDay(\Carbon\Carbon::today())->setPageName('new_leads_page')->links()}}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="column 2xl:ml-4">
        <div class="company-leads-chart">
            <header class="showCompanySubHeader 2xl:hidden">
                <h2>
                    Chart
                </h2>
            </header>
            <div class="bg-card rounded shadow">
                <new-leads-per-day-chart initial-data={{$company->getAmountOfNewLeadsPerDay(7)}}></new-leads-per-day-chart>
            </div>
        </div>
        <div class="employees">
            <header class="showCompanySubHeader flex justify-between">
                <h2>
                    Employees
                </h2>
                <a href="{{$company->path()}}/employees" class="py-1 button-primary">View all Employees</a>
            </header>
            @foreach($company->employees()->paginate(4, ["*"], "employees_page") as $employee)
            <div class="flex justify-between items-center bg-card rounded shadow py-4 px-5 mb-2">
                <div class="flex flex-1">
                    <p class="sm:w-1/2">{{$employee->name}}</p>
                    <p class="hidden sm:inline pl-4 border-l border-gray-300">{{$employee->pivot->role->name}}</p>
                </div>
                <div class="">
                    <button @click.prevent="$modal.show('edit-employee',{ 'employee': {{$employee}} })" class="button-primary mr-2">Edit</button>
                    <form method="POST" class="inline" action="{{$company->path()}}/employees/{{$employee->id}}">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <input type="submit" class="button-danger ml-3" value="Remove">
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        {{$company->employees()->paginate(4, ["*"], "employees_page")->links()}}
    </div>
</div>
<edit-company-modal :initial-company="{{$company}}"></edit-company-modal>
<edit-employee-modal :initial-company="{{$company}}" :roles="{{App\Models\Role::all()}}"></edit-employee-modal>

@endsection