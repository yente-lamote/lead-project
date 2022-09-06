<tr id="activity-{{$activity->id}}" class="main-row {{$loop->last?'remove-border-bottom':''}}">
    <td class="hidden md:table-cell">{{$activity->user->name}}</td>
    <td class="capitalize">{{ str_replace('_',' ',$activity->description)}}</td>
    <td class="hidden md:table-cell">{{$activity->updated_at}}</td>
    <td class="flex items-center mr-2 float-right md:mr-0 md:float-none">
        <div class="hidden md:block">
            <p class="text-gray-400">Removed:
                <span class="text-black">{{$activity->changes['name']}}</span>
            </p>
        </div>
        <open-changes-button :activity-id="{{$activity->id}}" class="ml-3 block md:hidden">
    </td>
</tr>
<tr id="changes-{{$activity->id}}" class="hidden changes">
    <td colspan="4">
            <div class="flex flex-col md:hidden pl-5 text-sm mb-3">
                <p class="text-gray-400">User</p>
                <p class="text-base">{{$activity->user->name}}</p>
                <p class="text-gray-400 mt-2">Updated at</p>
                <p class="text-base">{{$activity->updated_at}}</p>
                <p class="text-gray-400 mt-2">Removed:
                    <span class="text-black">{{$activity->changes['name']}}</span>
                </p>
            <div>
    </td>
</tr>


