<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Poppins:500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <script src="https://kit.fontawesome.com/a81368914c.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">

    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">

</head>

<body>
    <div id="app">
        @yield('header')

        <main class="">
            @yield('content')
        </main>
    </div>
</body>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.js"></script>

<script>
    document.getElementById("add_column").addEventListener("click", function() {
        var div = document.createElement("div");
        div.className = "column row mt-2";
        div.innerHTML = `
        <div class="col-md-6">
            <label for="column_name" class="form-label">Input&nbsp;Name:</label><br>
            <input type="text" class="form-control add_table_input" id="column_name" name="column_name[]" required>
        </div>
        <div class="col-md-6">
            <label for="column_type" class="form-label">Input&nbsp;Type:</label><br>
        <div class="row">
            <div class="col-md-10">
              <select class="form-select add_table_input" id="column_type" name="column_type[]" required>
              <option value="string">text</option>
                                    <option value="integer">number</option>
                                    <option value="file">file</option>
                                    <option value="date">Date</option>
              </select>
            </div>
            <div class="col-md-2">
            <button type="button" class="btn add_table_btn_delete btn-outline-danger remove-input-field"><i class="fas fa-trash"></i></button>
            </div>

        </div>

        </div>
        `;
        document.getElementById("columns").appendChild(div);

        // Get column type and name input fields
        var columnType = div.querySelector('#column_type');
        var columnName = div.querySelector('#column_name');

        // Add change event listener to column type input field
        columnType.addEventListener('change', function() {
            if (columnType.value === 'file') {
                // columnName.disabled = true;
                columnName.value = 'photo';
            }
        });
    });

    $(document).on('click', '.remove-input-field', function() {
        $(this).closest('.column').remove();

        // Remove the corresponding input fields from the backend
        var inputFieldName = $(this).closest('.column').find("input[name='column_name[]']").val();
        var inputFieldType = $(this).closest('.column').find("select[name='column_type[]']").val();
        $.ajax({
            url: "{{ route('delete_input_field') }}",
            type: "POST",
            data: {
                inputFieldName: inputFieldName,
                inputFieldType: inputFieldType,
                _token: "{{ csrf_token() }}"
            },
            success: function(data) {
                console.log(data);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
    });
</script>
</script>

<!-- <option value="string">radio</option>
<option value="string">checkbox</option> -->

<!-- <option value="string">email</option>
                                    <option value="file">file</option> -->

</html>
