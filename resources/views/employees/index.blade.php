@extends ('layouts.app')
@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
    @foreach($employees as $employee)
        <employee-card initial-employee="{{$employee}}" company-id="{{$company->id}}" initial-role="{{$employee->pivot->role}}"></employee-card>
    @endforeach
</div>
<edit-employee-modal :initial-company="{{$company}}" :roles="{{App\Models\Role::all()}}"></edit-employee-modal>
@endsection