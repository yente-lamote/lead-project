@extends ('layouts.app')
@section('content')
<div class="grid lg:grid-cols-4 md:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-4">
    @foreach($companies as $company)
        @include ('companies.card')
    @endforeach
</div>
@endsection