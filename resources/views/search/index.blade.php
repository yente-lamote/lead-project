@extends ('layouts.app')
@section('content')
<div>
    <div>
        <p class="text-md text-gray-400">Filters:</p>
        <div class="flex">
            <a href="/search?query={{request()->query('query')}}" class="border search-filter rounded-l-lg {{Request::is('search')?'selected':'not-selected'}}">All results</a>
            <a href="/search/leads?query={{request()->query('query')}}" class="border-t border-b search-filter {{Request::is('search/leads')?'selected':'not-selected'}}">Leads</a>
            <a href="/search/companies?query={{request()->query('query')}}" class="border search-filter rounded-r-lg {{Request::is('search/companies')?'selected':'not-selected'}}">Companies</a>
        </div>
    </div>
    @if(isset($leads))
        @if($leads->total())
        <section class="mt-4">
            <h2 class="text-md text-gray-400 mb-1">A total of <span class="text-gray-600 font-medium">{{$leads->total()}}</span> leads found</h2>
            <div class="bg-card rounded shadow px-6 py-1">
            @foreach($leads as $lead)
                @php
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
    @endif
    @if(isset($companies))
        @if($companies->total())
        <section class="mt-4">
            <h2 class="text-md text-gray-400 mb-1">A total of <span class="text-gray-600 font-medium">{{$companies->total()}}</span> companies found</h2>
            <div class="grid xl:grid-cols-4 lg:grid-cols-3 md:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-4">
                @foreach($companies as $company)
                    @include ('companies.card')
                @endforeach
            </div>
            <footer class="my-3">
                {{$companies->links()}}
            </footer>
        </section>
        @endif
    @endif
    @if(!isset($companies)||!$companies->total())
        @if(!isset($leads)||!$leads->total())
            <p class="text-lg mt-3">Nothing found</p>
        @endif
    @endif
</div>
@endsection