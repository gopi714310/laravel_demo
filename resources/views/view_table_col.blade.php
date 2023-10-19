@extends('layouts.app')
@if(Auth::check())
    @extends('layouts.nav')
@endif

@section('content')

<style>
    .view_table_btn {
        border-radius: 2px;
        background: #38d39f;
        border: 1px solid #38d39f;
        color: white !important;
        font-size: 15px;
        font-weight: 500;
        padding: 2px 10px 2px 10px;
    }

    .view_table_btn:hover {
        color: #38d39f !important;
        background-color: #f8fafc;
        border: 2px solid #38d39f;
    }

    .btn {
        border-radius: 2px;
    }

    .view_table_btn_back {
        background: #204d74;
        border: 1px solid #204d74;
        color: white;
    }

    .view_table_btn_back:hover {
        color: #204d74 !important;
        background-color: #f8fafc;
        border: 2px solid #204d74;
    }

    .view__form_column_card {
        border: none;
        -webkit-box-shadow: 0px 0px 6px 1px rgba(117, 117, 117, 1);
        -moz-box-shadow: 0px 0px 6px 1px rgba(117, 117, 117, 1);
        box-shadow: 0px 0px 6px 1px rgb(223 223 223);
    }

    .view_from_delete {
        color: white;
        background: #dc3545;
        border: #dc3545;
        font-size: 15px;
        padding: 3px 11px 3px 11px;
    }

    .view_from_delete:hover {
        color: #dc3545;
        background-color: #ffffff;
        border: 2px solid #dc3545;
    }
</style>

<img class="wave w-100" src="{{ asset('image/wave.png') }}" alt="Example Image">

<div class="container mt-5">
    <div class="card view__form_column_card">
        <div class="card-header" style="background: white;">
            <div class="d-flex justify-content-between">
                <p class="mb-0" style="font-size: 23px;font-weight: 500;">{{ ucfirst($tableName)}}</p>
                <div class="d-flex">
                    <a href="{{ route('form.create', $tableName) }}" class="btn view_table_btn d-flex  align-items-center">Add {{ $tableName }}</a>&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="{{ url('form_design') }}" class="btn view_table_btn_back d-flex  align-items-center">Back</a>&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="{{ route('table.download', $tableName) }}" class="btn view_table_btn d-flex align-items-center"><i class="fas fa-download"></i></a>
                </div>
            </div>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr class="text-center" style="background: #204d74;color: white;">
                        <th scope="col">S.NO</th>
                        @foreach($columns as $column)
                        <th>{{ ucfirst($column) }}</th>
                        @endforeach
                        <th>Action</th>
                        <!-- <th>Delete</th> -->
                    </tr>
                </thead>
                <tbody>
                    @php $count = 1 @endphp
                    @foreach ($data as $row)
                    <tr class="text-center">
                        <td>{{ $count++ }}</td>
                        @foreach ($columns as $column)

                        <td>{{ $row->$column }}</td>
                        @endforeach

                        <td class="d-flex justify-content-center">
                            <a href="{{ route('table.edit', ['tableName' => $tableName, 'id' => $row->id]) }}" class="btn view_table_btn"><i class="fas fa-pen"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;
                            <form action="{{ route('delete_row', ['tableName' => $tableName, 'id' => $row->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn view_from_delete btn-sm delete-table-btn"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

 <!-- SweetAlert success message -->
 @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: 'Success',
                    text: '{{ session('success') }}',
                    icon: 'success',
                });
            });
        </script>
   @elseif (session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                title: 'Error',
                text: '{{ session('error') }}',
                icon: 'error',
            });
        });
    </script>
@endif

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Select all delete form buttons
        var deleteButtons = document.querySelectorAll('.delete-table-btn');

        // Attach event listener to each delete form button
        deleteButtons.forEach(function (button) {
            button.addEventListener('click', function (event) {
                event.preventDefault();
                var form = this.closest('form');

                // Show the SweetAlert confirmation dialog
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action cannot be undone!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then(function (result) {
                    if (result.isConfirmed) {
                        // Submit the form if the user confirms
                        form.submit();
                    }
                });
            });
        });
    });
</script>


@endsection
