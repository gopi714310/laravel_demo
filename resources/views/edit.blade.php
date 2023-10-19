@extends('layouts.app')
@if(Auth::check())
    @extends('layouts.nav')
@endif

@section('content')

<style>
    .update_table_form_btn {
        border-radius: 2px;
        background: #38d39f;
        border: 1px solid #38d39f;
        color: white !important;
        font-size: 15px;
        font-weight: 500;
    }

    .update_table_form_btn:hover {
        color: #38d39f !important;
        background-color: #f8fafc;
        border: 2px solid #38d39f;
    }

    .update_table_form_input {
        border-radius: 0px;
        border: none;
        border-bottom: 1px solid #204d74;
        background: transparent;
        font-size: 17px;
    }

    .update_table_form_card {
        border: none;
        -webkit-box-shadow: 0px 0px 6px 1px rgba(117, 117, 117, 1);
        -moz-box-shadow: 0px 0px 6px 1px rgba(117, 117, 117, 1);
        box-shadow: 0px 0px 6px 1px rgb(223 223 223);
    }

    .update_card_header {
        color: #ffffff;
        border-bottom: 3px solid #38d39f;
        background: #204d74;
    }

    .form-floating>label {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        padding: 1rem 0.75rem;
        overflow: hidden;
        text-align: start;
        text-overflow: ellipsis;
        white-space: nowrap;
        pointer-events: none;
        border: 1px solid transparent;
        transform-origin: 0 0;
        transition: opacity 0.1s ease-in-out, transform 0.1s ease-in-out;
        color: #999;
        font-size: 15px;
    }

    .form-control:focus {
        color: #204d74;
        background-color: #f8fafc00;
        border-bottom: 2px solid #38d39f;
        outline: 0;
        box-shadow: 0 0 0 0.25rem rgb(13 110 253 / 0%);
    }

    .form-floating>.form-control:focus~label,
    .form-floating>.form-control:not(:placeholder-shown)~label,
    .form-floating>.form-control-plaintext~label,
    .form-floating>.form-select~label {
        opacity: 1;
        transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
        color: #38d39f;
        font-size: 18px;
        font-weight: 600;
        padding-left: 0px;
    }

    .form-floating>.form-control:focus,
    .form-floating>.form-control:not(:placeholder-shown),
    .form-floating>.form-control-plaintext:focus,
    .form-floating>.form-control-plaintext:not(:placeholder-shown) {
        padding-top: 2.625rem;
        padding-bottom: 0.625rem;
    }

    .update_table_form_image {
        display: block;
        width: 50%;
        height: 50%;
        padding-top: 35px;
    }
</style>

<img class="wave w-100" src="{{ asset('image/wave.png') }}" alt="Example Image">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card update_table_form_card">

                <h4 class="card-header update_card_header">
                    Update {{ucfirst($tableName)}}
                </h4>
                <div class="card-body">
                    <form method="POST" action="{{ route('table.update', [$tableName, $data->id]) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" value="{{ $data->id }}">
                        <input type="hidden" name="table_name" value="{{ $tableName }}">
                        @foreach ($inputs as $column => $rules)
                        <div class="form-floating  mb-3">
                            @if(strpos($rules, 'date') !== false)
                            <input type="date" class="form-control update_table_form_input @error($column) is-invalid @enderror" id="{{ $column }}" name="{{ $column }}" value="{{ old($column, $data->$column) }}" {{ $rules }}>
                            @elseif (strpos($rules, 'image') !== false)
                            @if($data->$column)
                            <img src="{{ asset('storage/' . $data->$column) }}" alt="{{ $column }}" class="update_table_form_image">
                            @endif
                            <input type="file" class="form-control update_table_form_input" style="padding-top: 1.5rem;" id="{{ $column }}" name="{{ $column }}" {{ $rules }} multiple>
                            @elseif(strpos($rules, 'integer') !== false && strpos($rules, 'bigInteger') === false)
                            <input type="number" class="form-control update_table_form_input @error($column) is-invalid @enderror" id="{{ $column }}" name="{{ $column }}" value="{{ old($column, $data->$column) }}" {{ $rules }}>
                            @elseif(strpos($rules, 'Integer') !== false)
                            <input type="number" class="form-control update_table_form_input @error($column) is-invalid @enderror" id="{{ $column }}" name="{{ $column }}" value="{{ old($column, $data->$column) }}" {{ $rules }}>
                            @else
                            <input type="text" class="form-control update_table_form_input @error($column) is-invalid @enderror" id="{{ $column }}" name="{{ $column }}" value="{{ old($column, $data->$column) }}" {{ $rules }}>
                            @endif
                            <label for="{{ $column }}" class="ps-0">{{ ucfirst(str_replace('_', ' ', $column)) }}</label>

                            @error($column)
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @endforeach
                        <br>
                        <button type="submit" class="btn update_table_form_btn"> Update {{ucfirst($tableName)}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
