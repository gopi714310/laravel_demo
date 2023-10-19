@extends('layouts.app')
@if(Auth::check())
    @extends('layouts.nav')
@endif

@section('content')

<style>
    .view__form_name_card {
        border: none;
        -webkit-box-shadow: 0px 0px 6px 1px rgba(117, 117, 117, 1);
        -moz-box-shadow: 0px 0px 6px 1px rgba(117, 117, 117, 1);
        box-shadow: 0px 0px 6px 1px rgb(223 223 223);
    }

    .view_form_name_btn {
        border-radius: 2px;
        background: #38d39f;
        border: 1px solid #38d39f;
        color: white !important;
        font-size: 15px;
        font-weight: 500;
        /* padding: 2px 10px 2px 10px; */
    }

    .view_form_name_btn:hover {
        color: #38d39f !important;
        background-color: #f8fafc;
        border: 2px solid #38d39f;
    }

    .btn {
        border-radius: 2px;
    }

    .link_table {
        display: block;
        text-align: center;
        text-decoration: none;
        color: #38d39f;
        font-size: 0.9rem;
        transition: .3s;
    }

    .view_from_delete {
        color: white;
        background: #dc3545;
        border: #dc3545;
        font-size: 15px;
        font-weight: 500;
        padding: 7px 13px 7px 13px;
    }

    .view_from_delete:hover {
        color: #dc3545;
        background-color: #ffffff;
        border: 2px solid #dc3545;
    }

    .form_name:hover>.link_table {
        display: block;
        text-align: center;
        text-decoration: none;
        color: #204d74;
        font-size: 0.9rem;
        transition: .3s;
    }

    .btn-share-form {
        border-radius: 2px;
        background-color: #204d74;
        border: none;
        color: white;
        font-size: 15px;
        font-weight: 500;
        padding: 7px 13px;
        margin-right: 10px;
    }

    .btn-share-form:hover {
        background-color: #153657;
        cursor: pointer;
    }
</style>

<img class="wave w-100" src="{{ asset('image/wave.png') }}" alt="Example Image">

<div class="container mt-5">
    <div class="card view__form_name_card">
        <!-- <div class="card-header">
            <div class="d-flex justify-content-between">
                <p class="mb-0" style="font-size: 23px;font-weight: 500;">View form Name</p>
                <div class="d-flex">
                </div>
            </div>
        </div> -->

        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr class="text-center" style="background: #204d74;color: white;">
                        <th scope="col">S.NO</th>
                        <th scope="col">Form Name</th>
                        <th>Action</th>
                        <!-- <th>Delete</th> -->
                    </tr>
                </thead>
                <tbody>
                    @php $count = 1 @endphp
                    @foreach($tables as $table)

                    <tr class="text-center">
                        <td>{{ $count++ }}</td>
                        <td class="form_name">
                            <a class="link_table" href="{{ route('columns.show', ['tableName' => $table->{'Tables_in_'.config('database.connections.mysql.database')}]) }}">
                                {{ $table->{'Tables_in_'.config('database.connections.mysql.database')} }}
                            </a>
                        </td>

                        @foreach ($table->columnNames as $index => $columnName)
                        <td>{{ $columnName }}</td>
                        <td>{{ $columnTypes[$index] }}</td>
                        @endforeach

                        <td class="d-flex justify-content-center">
                            <!-- <a href="{{ route('columns.updateform', ['tableName' => $table->{'Tables_in_'.config('database.connections.mysql.database')}, 'columnName' => $table->columnNames]) }}" class="btn view_form_name_btn"><i class="fas fa-pen"></i></a>&nbsp;&nbsp;&nbsp;&nbsp; -->

                            <form method="POST" action="{{ route('delete_table', ['tableName' => $table->{'Tables_in_'.config('database.connections.mysql.database')}]) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn view_from_delete delete-table-btn"><i class="fas fa-trash"></i></button>
                            </form>&nbsp;&nbsp;
                            <!-- Share link -->
                            <button class="btn btn-share-form" onclick="shareForm('{{ $table->{'Tables_in_'.config('database.connections.mysql.database')} }}')"><i class="fas fa-share-alt"></i></button>
                            <!-- <a href="http://127.0.0.1:8000/add_form_table/{{ $table->{'Tables_in_'.config('database.connections.mysql.database')} }}">Share</a> -->
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
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Success',
            text: '{{ session('
            success ') }}',
            icon: 'success',
        });
    });
</script>
@endif

<!-- SweetAlert confirmation dialog -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Select all delete form buttons
        var deleteButtons = document.querySelectorAll('.delete-table-btn');

        // Attach event listener to each delete form button
        deleteButtons.forEach(function(button) {
            button.addEventListener('click', function(event) {
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
                }).then(function(result) {
                    if (result.isConfirmed) {
                        // Submit the form if the user confirms
                        form.submit();
                    }
                });
            });
        });
    });
</script>

<script>
    function shareForm(tableName) {
        var baseUrl = window.location.origin;
        var shareLink = baseUrl + '/add_form_table/' + tableName;

        // Logic to handle sharing the form link goes here
        console.log('Share link: ' + shareLink);

        // Copy the share link to the clipboard
        navigator.clipboard.writeText(shareLink)
            .then(function() {
                // Link copied successfully
                alert('Link copied to clipboard: ' + shareLink);
                // You can now send the link to non-registered users via email or other means
            })
            .catch(function(error) {
                // Failed to copy the link
                console.error('Failed to copy link: ' + error);
            });
    }
</script>



@endsection
