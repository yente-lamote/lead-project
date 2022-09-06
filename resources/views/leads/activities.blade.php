@if(count($lead->allActivities()))
    <table class="activity-log-table w-full table-fixed">
        <tr class="pb-3 pt-2">
            <th class="w-1/4 pl-2 md:pl-5 hidden md:table-cell">User</th>
            <th class="w-3/4 md:w-1/4 pl-2 md:pl-0 ">Action</th>
            <th class="w-1/4 hidden md:table-cell">Updated at</th>
            <th class="w-1/4">Changes</th>
        </tr>
        @foreach($lead->allActivities() as $activity)
            @if(View::exists("activities.$activity->description"))
                @include("activities.$activity->description",['forCompany'=>false])
            @else
                @include('activities.default',['forCompany'=>false])
            @endif
        @endforeach
    </table>
    {{$lead->allActivities()->links()}}
@else
    <p>No changes were made to this lead.</p>
@endif
