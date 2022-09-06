<tr id="activity-{{$activity->id}}" class="main-row {{$loop->last?'remove-border-bottom':''}}">
    <td class="hidden md:table-cell">{{$activity->user->name}}</td>
    <td class="capitalize">{{ str_replace('_',' ',$activity->description)}}</td>
    <td class="hidden md:table-cell">{{$activity->updated_at}}</td>
    <td class="flex items-center mr-2 float-right md:mr-0 md:float-none">
        <span class="md:inline hidden">View changes</span>
        <open-changes-button :activity-id="{{$activity->id}}" class="ml-3">
    </td>
</tr>
<tr id="changes-{{$activity->id}}" class="hidden changes">
    <td colspan="4">
        <table>
            <thead>
                <tr class="hidden md:table-row">
                    <th class="font-medium visible md:invisible">Attribute</th>
                    <th class="font-medium">Before</th>
                    <th class="font-medium">After</th>
                </tr>
            </thead>
            <tbody class="w-full md:w-auto">
            <div class="flex flex-col md:hidden pl-5">
                <p class="text-gray-400 text-sm">User</p>
                <p>{{$activity->user->name}}</p>
                <p class="text-gray-400 text-sm mt-2">Updated at</p>
                <p>{{$activity->updated_at}}</p>
                <p class="text-gray-400 text-sm mt-2 mb-1">Changes<p>
            <div>
            <tr class="flex flex-col md:table-row pl-4 text-sm md:text-base">
                <td>
                    <p class="md:hidden text-xs text-gray-400">Attribute</p>
                    <span class="md:font-medium capitalize">
                        {{ str_replace('_',' ',$activity->subject->attribute->name)}}
                    </span>
                </td>
                <td>
                    <p class="md:hidden text-xs text-gray-400">Before</p>
                    {{$activity->changes['before']['value']}}
                </td>
                <td>
                    <p class="md:hidden text-xs text-gray-400">After</p>
                    {{$activity->changes['after']['value'] }}
                </td>            
            </tr>
            </tbody>
        </table>
    </td>
</tr>