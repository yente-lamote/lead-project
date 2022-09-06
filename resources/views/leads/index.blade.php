@extends ('layouts.app')
@section('content')
<header class="my-1 flex items-center justify-between">
    <div>
        <h1 class="text-2xl inline mr-3">Leads</h1>
        @if($leads->total())
        <span class="text-gray-400 text-sm">{{$leads->total()}} Total</span>
        @endif
    </div>
    @if($companies)
        <div class="flex items-center">
            <select-action-button class="mr-4" url-prefix="/leads" url-suffix="" button-text="Archive"></select-action-button>
            <select-company initial-companies="{{$companies}}" page="leads" initial-selected-company="{{request()->company->id}}"></select-company>
        </div>
    @else
        <p>You currently don't have access to any company</p>
    @endif
</header>
<div>
@if($leads->total())
@if($leads->total())
        <section class="mt-4">
            <h2 class="text-md text-gray-400 mb-1">A total of <span class="text-gray-600 font-medium">{{$leads->total()}}</span> leads found</h2>
            <div class="bg-card rounded shadow px-6 py-1">
            @foreach($leads as $lead)
            @php
                //zodat de status als object weergegeven wordt in lead variable en niet alleen status id
                $lead->status;
                $lead->company;
            @endphp
                <lead-teaser :lead="{{$lead}}" path="{{$lead->path()}}" company-path="{{$lead->company->path()}}" fullname="{{$lead->fullname()}}" last="{{$loop->last}}"></lead-teaser>
            @endforeach
                <footer class="my-3">
                {{$leads->links()}}
                </footer>
            </div>
        </section>
        @endif
    @else
        <p class="mt-3">{{request()->company->name}} doesn't have access to any leads</p>
    @endif
</div>

@endsection