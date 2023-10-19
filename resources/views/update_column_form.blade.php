@extends('layouts.app')
@if(Auth::check())
    @extends('layouts.nav')
@endif

@section('content')


<form method="POST" action="{{ route('columns.update') }}">
    @csrf
    <div class="form-group">
        <label for="column_name">Column Name:</label>
        <input type="text" name="column_name" class="form-control" value="{{ $columnName }}">
    </div>
    <div class="form-group">
        <label for="column_type">Column Type:</label>
        <select name="column_type" class="form-control">
            <option value="string">String</option>
            <option value="integer">Integer</option>
            <option value="text">Text</option>
            <!-- add more options based on your requirements -->
        </select>
    </div>
    <input type="hidden" name="table_name" value="{{ $tableName }}">
    <input type="text" name="old_column_name" value="{{ $columnName }}">
    <button type="submit" class="btn btn-primary">Update Column</button>
</form>




@endsection
