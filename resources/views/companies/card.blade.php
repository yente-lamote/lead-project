<div class="bg-card shadow rounded p-6">
    <div class="flex justify-between">
        <div class="rounded-image-container w-5/12">
            <a href="{{$company->path()}}">
                @if(file_exists('assets/images/companies/'.$company->id.'.png')) 
                    <img class="rounded-full shadow hover:opacity-70" src="{{ URL::to('/assets/images/companies/'.$company->id.'.png') }}" alt="company logo">
                @else
                    <img class="rounded-full shadow hover:opacity-70" src="{{ URL::to('/assets/images/companies/default.png') }}" alt="company logo">
                @endif
            </a>
        </div>
        <div>
            <p class="mt-2 text-right">
                <span class="text-gray-500 font-medium">{{$company->countAccessibleLeads()}}</span>
                <a class="text-md text-gray-400 hover:text-blue-400" href="{{$company->path()}}/leads">Leads</a>
            </p>
            <p class="mt-1 text-right">
                <span class="text-gray-500 font-medium">{{$company->countEmployees()}}</span>
                <a class="text-md text-gray-400 hover:text-blue-400" href="{{$company->path()}}/employees">Employees</a>
            </p>
        </div>
    </div>
    <h3 class="text-xl font-semibold mt-5 hover:text-blue-600"><a href="{{$company->path()}}"> {{$company->name}}</a></h3>
    <p class="text-gray-400 text-sm mb-2">{{ Illuminate\Support\Str::limit($company->description,90)}}</p>
</div>