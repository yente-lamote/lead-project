@extends ('layouts.app')
@section('content')
@if ($errors->any())
    <div class="text-red-600">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<h1 class="text-3xl text-gray-400">Lead {{$lead->id}}</h1>
<page-wrapper>
    <div slot="status-tab">
        <h2 class="text-xl mt-2 text-gray-400">Status</h2>
        <div class="max-w-md">
            <name-description-dropdown :attributes="{{App\Models\Status::all()}}" :initial-attribute="{{$lead->status}}" attribute-name="status" post-url="{{$lead->path()}}/status"></name-description-dropdown>
        </div>
    </div>
    <div slot="attributes-tab">
        <form action="{{$lead->path()}}" method="POST">
            <h2 class="text-xl mt-2 text-gray-400">Default attributes</h2>
            @foreach($lead->updateableDefaultAttributes() as  $key => $value)
            @csrf
            <div class="show-lead-attribute-container {{ !$loop->last ? 'sm:border-b' :'' }}">
                    <label for="{{$key}}" class="show-lead-label">
                        {!! str_replace('_',' ',$key)!!}
                    </label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                        <input type="text" value="{{$value}}" name="{{$key}}" id="{{$key}}" autocomplete="given-name" class="show-lead-input">
                    </div>
                </div>
            @endforeach
            <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start items-center my-2">
                <h2 class="text-xl mt-2 text-gray-400 inline">Extra attributes</h2>
                <button @click.prevent="$modal.show('add-extra-attribute')" 
                    class="button-primary pt-1 w-40 float-right sm:float-none">Add extra attribute</button>
            </div>
            @forelse($lead->attributes as $attribute)
                <div class="show-lead-attribute-container {{ !$loop->last ? 'sm:border-b' :'' }}">
                    <label for="{{$attribute->id}}" class="show-lead-label">
                        {{$attribute->name}}
                    </label>
                    <div class="mt-1 sm:mt-0 sm:col-span-2">
                        <input type="text" value="{{$attribute->pivot->value}}" name="extra_attributes[{{$attribute->id}}]" id="{{$attribute->name}}" autocomplete="given-name" class="show-lead-input">
                    </div>
                </div>
            @empty
                <p>No extra attributes found for this lead. <a class="text-blue-400">Add some</a></p>
            @endforelse
            <button type="submit" class="button-primary mt-2">Save changes</button>
        </form>
        <add-extra-attribute-modal :initial-lead="{{$lead}}"></add-extra-attribute-modal>
    </div>
    <div slot="activity-logs-tab">
        @include ('leads.activities')
    </div>
    <div slot="companies-tab" class=" px-4">
        <h2 class="text-xl mt-2 text-gray-400">
            Lead owner
        </h2>
        <a href="{{$lead->company->path()}}" class="hover:text-blue-600">
            {{$lead->company->name}}
        </a>
        <div class="max-w-3xl md:flex justify-between items-center">
            <h2 class="text-xl mt-2 text-gray-400">
                Companies with access to this lead
            </h2>
            <div class="flex">
                <button @click="$modal.show('add-extra-company')" class="button-primary mr-4">Add company</button>
                <select-action-button url-prefix="/leads/{{$lead->id}}/extra-companies" url-suffix="" button-text="Remove company"></select-action-button>
            </div>
        </div>
        <div>
            @foreach($lead->companies as $company)
                <div class="flex items-center">
                    <input class="ml-2 mr-4 mt-0.5" type="checkbox" aria-label="Select" value="{{$company->id}}">
                    <a href="{{$company->path()}}" class="hover:text-blue-600">{{$company->name}}</a>
                </div>
            @endforeach
        </div>
    </div>
</page-wrapper>
<add-extra-company-modal :lead-id="{{$lead->id}}" :companies="{{App\Models\Company::all()}}"></add-extra-company-modal>
@endsection
