@extends ('layouts.app')
@section('content')
@if(count($company->activities()->latest()->get()))
<header class="flex justify-between items-center">
    <h1 class="text-3xl text-gray-600 mb-6">{{$company->name}}'s activity log</h1>
    <a href="{{$company->path()}}" class="button-primary self-start">View company</a>
</header>
<div class="p-4 rounded-md card">
    <table class="activity-log-table w-full table-fixed">
        <tr class="pb-3 pt-2">
            <th class="w-1/4 pl-2 md:pl-5 hidden md:table-cell">User</th>
            <th class="w-3/4 md:w-1/4 pl-2 md:pl-0 ">Action</th>
            <th class="w-1/4 hidden md:table-cell">Updated at</th>
            <th class="w-1/4">Changes</th>
        </tr>
        @foreach($company->activities()->latest()->paginate(10) as $activity)
            @if(View::exists("activities.$activity->description"))
                @include("activities.$activity->description",['forCompany'=>true])
            @else
                @include('activities.default',['forCompany'=>true])
            @endif
        @endforeach
    </table>
    {{$company->activities()->latest()->paginate(10)->links()}}
@else
    <p>No Activities were found for this company</p>
</div>
@endif
@endsection