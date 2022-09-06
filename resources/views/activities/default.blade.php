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
        @if($forCompany&&$activity->subject)
            <div class="flex md:block justify-between mr-5">
                <div class="mr-4 inline-flex flex-col">
                    <span class="md:ml-0 ml-5 text-gray-400 text-sm">Lead ID</span>
                    <span class="md:ml-0 ml-5 mb-2">{{$activity->subject->id}}</span>
                </div>
                <div class="inline-block pt-1">
                <a href="{{$activity->subject->path()}}" class="button-primary">View lead</a>
                </div>
            </div>
        @endif
        <table>
            <thead>
                <tr class="hidden md:table-row">
                    <th class="font-medium visible md:invisible">Attribute</th>
                    <th class="font-medium">Before</th>
                    <th class="font-medium">After</th>
                    @if($activity->note)
                        <th class="font-medium">Note</th>
                    @endif
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
            @foreach ($activity->changes['before'] as $key=>$value)
            <tr class="flex flex-col md:table-row pl-4 text-sm md:text-base">
                <td>
                    <p class="md:hidden text-xs text-gray-400">Attribute</p>
                    <span class="md:font-medium capitalize">
                        {{ str_replace('_',' ',$key)}}
                    </span>
                </td>
                <td>
                    <p class="md:hidden text-xs text-gray-400">Before</p>
                        {{$value}}
                </td>
                <td>
                    <p class="md:hidden text-xs text-gray-400">After</p>
                    
                    {{$activity->changes['after'][$key] }}
                </td>   
                @if($activity->note)
                    <td>
                        <p class="md:hidden text-xs text-gray-400">Note</p>
                        {{$activity->note }}
                    </td>  
                @endif       
            </tr>
            @endforeach
            </tbody>
        </table>
        
    </td>
</tr>