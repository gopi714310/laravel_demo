@extends('layouts.app')
@if(Auth::check())
@extends('layouts.nav')
@endif

@section('content')

<style>
    .add_table_btn {
        border-radius: 2px;
        background: #38d39f;
        border: 1px solid #38d39f;
        color: white !important;
        font-size: 15px;
        font-weight: 500;
    }

    .add_table_btn:hover {
        color: #38d39f !important;
        background-color: #f8fafc;
        border: 2px solid #38d39f;
    }

    .add_table_input {
        border-radius: 2px;
    }

    .add_table_card {
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
</style>

<img class="wave w-100" src="{{ asset('image/wave.png') }}" alt="Example Image">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card add_table_card">
                <h4 class="card-header update_card_header">
                    {{ __('message.addform') }}
                </h4>

                <div class="card-body p-5">
                    <form method="POST" action="{{ route('create_table') }}">
                        @csrf
                        <label for="table_name" class="form-label">Form Name:</label>
                        <input type="text" class="form-control add_table_input" id="table_name" name="table_name" required>

                        <div id="columns">
                            <div class="column row mt-2">
                                <div class="col-md-6">
                                    <label for="column_name" class="form-label">Input&nbsp;Name:</label><br>
                                    <input type="text" class="form-control add_table_input" id="column_name" name="column_name[]" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="column_type" class="form-label add_table_input">Input&nbsp;Type:</label><br>
                                    <select class="form-select add_table_input" id="column_type" name="column_type[]" required onchange="updateColumnName(this)">
                                        <option value="string">text</option>
                                        <option value="integer">number</option>
                                        <option value="file">file</option>
                                        <!-- <option value="string">radio</option>
                                    <option value="string">checkbox</option> -->
                                        <option value="date">Date</option>
                                        <!-- <option value="select">Select</option> Add the new option -->
                                    </select>
                                    <div id="select_option_input" style="display:none;"> <!-- Add a new input field and hide it initially -->
                                        <label for="select_option" class="form-label">Select Options (comma separated):</label><br>
                                        <input type="text" class="form-control add_table_input" id="select_option" name="select_option[]" />
                                    </div>
                                </div>

                            </div>
                        </div><br>

                        <button type="button" class="btn add_table_btn" id="add_column">Add Column</button>

                        <button type="submit" class="btn add_table_btn">Create Form</button>

                        <a href="{{url('form_design')}}" class="btn add_table_btn">view Form</a>

                    </form>
                </div>


                <!-- <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div> -->
            </div>
        </div>
    </div>
</div>

<script>
    // Check if selected column type is "file"
    var columnType = document.getElementById('column_type');
    var columnName = document.getElementById('column_name');
    columnType.addEventListener('change', function() {
        if (columnType.value === 'file' && columnName.value === '') {
            // columnName.disabled = true;
            columnName.value = 'photo';
        }
    });

    function updateColumnName(selectElement) {
        // get the input field element
        const inputElement = selectElement.parentElement.previousElementSibling.querySelector('input');
        // replace spaces with underscores in the input field name
        inputElement.name = 'column_name[]'.replace(' ', '_');
    }

    function updateColumnName(element) {
        if (element.value == "select") {
            document.getElementById('select_option_input').style.display = 'block';
        } else {
            document.getElementById('select_option_input').style.display = 'none';
        }
    }
</script>



@endsection
