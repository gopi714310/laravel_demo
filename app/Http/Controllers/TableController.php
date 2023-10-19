<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use App\Models\FormTable;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TableDataExport;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Illuminate\Support\Facades\DB;


class TableController extends Controller
{
    public function create(Request $request)
    {
        $tableName = $request->input('table_name');
        $columnNames = $request->input('column_name');
        $columnTypes = $request->input('column_type');

        // Replace any spaces in column names with underscores
        $columnNames = array_map(function ($name) {
            return str_replace(' ', '_', $name);
        }, $columnNames);

        Schema::create($tableName, function (Blueprint $table) use ($columnNames, $columnTypes) {
            $table->id();

            for ($i = 0; $i < count($columnNames); $i++) {
                $columnName = $columnNames[$i];
                $columnType = $columnTypes[$i];

                switch ($columnType) {
                    case 'string':
                        $table->string($columnName);
                        break;
                    case 'file':
                        $table->string('photo');
                        break;
                    case 'integer':
                        $table->bigInteger($columnName)->unsigned();
                        break;
                    case 'float':
                        $table->float($columnName);
                        break;
                    case 'date':
                        $table->date($columnName);
                        break;
                }
            }
            $table->timestamps();
        });

        Session::flash('success', 'Table created successfully!');

        return redirect('form_design');
    }

    public function index()
    {
        $allTables = Schema::getAllTables();

        $excludeTables = ['failed_jobs', 'migrations', 'password_resets', 'personal_access_tokens', 'users'];
        $tables = array_filter($allTables, function ($table) use ($excludeTables) {
            return !in_array($table->Tables_in_dynamic_form, $excludeTables);
        });

        $tableName = '';
        $columnNames = [];
        $columnTypes = [];

        // If a table name is selected, get the column names and types
        if (request()->has('table_name')) {
            $tableName = request()->input('table_name');
            $columns = Schema::getColumnListing($tableName);

            // Debugging code
            dd($tableName, $columns);

            foreach ($columns as $column) {
                $columnType = Schema::getColumnType($tableName, $column);
                $columnNames[] = $column;
                $columnTypes[] = $columnType;
            }
        }

        $tables = array_filter($tables, function ($table) use ($columnNames) {
            $tableName = $table->{'Tables_in_' . config('database.connections.mysql.database')};
            $table->columnNames = $columnNames;
            return !in_array($tableName, $columnNames);
        });

        return view('form_design', compact('tables', 'tableName', 'columnNames', 'columnTypes'));
    }

    public function showColumns($tableName)
    {

        $data = DB::table($tableName)->get();
        $columns = Schema::getColumnListing($tableName);
        $columns = array_diff($columns, ['id', 'created_at', 'updated_at']);
        return view('view_table_col', compact('tableName', 'data', 'columns'));
    }

    public function deleteRow($tableName, $id)
    {
        DB::table($tableName)->where('id', $id)->delete();

        return redirect()->route('columns.show', ['tableName' => $tableName])->with('success', 'Data deleted successfully');
    }

    public function edit($tableName, $id)
    {
        $data = DB::table($tableName)->where('id', $id)->first();
        $columns = Schema::getColumnListing($tableName);
        $columns = array_diff($columns, ['id', 'created_at', 'updated_at']);

        $inputs = [];
        foreach ($columns as $column) {
            $type = DB::connection()->getDoctrineColumn($tableName, $column)->getType()->getName();

            switch ($type) {
                case 'string':
                    if ($column === 'photo') {
                        $inputs[$column] = 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
                    } else {
                        $inputs[$column] = 'required|string|max:255';
                    }
                    break;
                case 'integer':
                case 'bigint':
                    $inputs[$column] = 'required|numeric';
                    break;
                case 'date':
                    $inputs[$column] = 'required|date';
                    break;
                case 'datetime':
                    $inputs[$column] = 'required|date';
                    break;
                    // Add more cases as needed for other data types
            }
        }
        return view('edit', compact('tableName', 'data', 'columns', 'inputs'));
    }

    public function update(Request $request, $tableName, $id)
    {

        // Validate input data in the format of "dd/mm/yyyy"
        $rules = [
            'date' => 'required|regex:/^\d{2}\/\d{2}\/\d{4}$/'
        ];

        // Validate input data in the format of "hh:mm:ss"
        $rules = [
            'time' => 'required|regex:/^\d{2}:\d{2}:\d{2}$/'
        ];

        // Validate input data in the format of "yyyy-mm-dd"
        $rules = [
            'date' => [
                'required',
                function ($attribute, $value, $fail) {
                    $date = \DateTime::createFromFormat('Y-m-d', $value);
                    if (!$date || $date->format('Y-m-d') !== $value) {
                        $fail($attribute . ' is invalid.');
                    }
                },
            ],
        ];

        // Validate input data in the format of "hh:mm:ss"
        $rules = [
            'time' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^([01]\d|2[0-3]):([0-5]\d):([0-5]\d)$/', $value)) {
                        $fail($attribute . ' is invalid.');
                    }
                },
            ],
        ];


        $tableName = $request->input('table_name');
        $data = [];

        foreach (Schema::getColumnListing($tableName) as $column) {
            if ($column == 'photo') {
                // Handle image upload and storage
                if ($request->hasFile('photo')) {
                    $photo = $request->file('photo');
                    $imagePath = $photo->store('public/image');
                    $data[$column] = str_replace('public/', '', $imagePath);
                }
            } elseif ($column == $rules) {
                $data[$column] = Carbon::createFromFormat('d/m/Y', $request->input($column))->format('Y-m-d');
            } else {
                $data[$column] = $request->input($column);
            }
        }

        // Add id, created_at, and updated_at values to the data array
        $data['created_at'] = Carbon::now();
        $data['updated_at'] = Carbon::now();

        // DB::table($tableName)->insert($data);

        DB::table($tableName)->where('id', $id)->update($data);


        return redirect()->route('columns.show', ['tableName' => $tableName])->with('success', 'Data updated successfully');
    }

    public function tabledelete($tableName)
    {
        // Perform any necessary checks to ensure the user is authorized to delete the table
        // ...

        // Delete the table from the database
        Schema::dropIfExists($tableName);

        // Redirect the user to a success page or to the previous page

        return redirect()->back()->with('success', 'Table deleted successfully.');
    }

    public function showUpdateForm(Request $request)
    {
        $tableName = $request->input('tableName');
        $columnName = $request->input('columnName');
        $columnType = $request->input('columnType');

        return view('update_column_form', compact('tableName', 'columnName', 'columnType'));
    }

    public function updatecreate(Request $request)
    {
        $tableName = $request->input('table_name');
        $oldColumnName = $request->input('old_column_name');
        $newColumnName = $request->input('new_column_name');
        $newColumnType = $request->input('new_column_type');

        Schema::table($tableName, function (Blueprint $table) use ($oldColumnName, $newColumnName, $newColumnType) {
            $table->renameColumn($oldColumnName, $newColumnName);

            switch ($newColumnType) {
                case 'string':
                    $table->string($newColumnName);
                    break;
                case 'file':
                    $table->string($newColumnName);
                    break;
                case 'integer':
                    $table->bigInteger($newColumnName)->unsigned();
                    break;
                case 'float':
                    $table->float($newColumnName);
                    break;
                case 'date':
                    $table->date($newColumnName);
                    break;
            }
        });
        // Session::flash('success', 'Table created successfully!');
        return redirect('form_design')->with('success', 'Column updated successfully!');
    }

    public function downloadExcel($tableName)
    {
        // Retrieve the data for the given table
        $columns = array_diff(DB::getSchemaBuilder()->getColumnListing($tableName), ['created_at', 'updated_at']);

        // if (!in_array('photo', $columns)) {
            // 'photo' column is not present in the table
        //     return redirect()->back()->with('error', 'The table does not have a "photo" column.');
        // }

        $data = DB::table($tableName)
            ->select(array_diff($columns, ['created_at', 'updated_at']))
            ->get();

        // Modify the data to replace image paths with the actual images
        $data = $data->map(function ($item) {
            if (property_exists($item, 'photo')) {
                // Retrieve the image from the storage path
                $photoPath = public_path('storage/' . $item->photo);
                if (file_exists($photoPath)) {
                    $item->photo = $photoPath;
                }
            }

            return $item;
        });

        // Create a new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Add headers to the worksheet
        $headerRow = 1;
        foreach ($columns as $columnIndex => $columnName) {
            $headerCell = $sheet->getCellByColumnAndRow($columnIndex + 1, $headerRow);
            $headerCell->setValue($columnName);
        }

        // Add the data to the worksheet
        $dataRow = $headerRow + 1;
        foreach ($data as $item) {
            $columnIndex = 1;
            foreach ($columns as $columnName) {
                $cell = $sheet->getCellByColumnAndRow($columnIndex, $dataRow);
                $cell->setValue($item->$columnName);
                $columnIndex++;
            }
            $dataRow++;
        }

        // Add images to the worksheet
        $imageRow = $headerRow + 1;
        foreach ($data as $item) {
            if (property_exists($item, 'photo')) {
                $imagePath = $item->photo;
                $drawing = new Drawing();
                $drawing->setPath($imagePath);
                $drawing->setWidth(100);
                $drawing->setHeight(100);

                $startCell = 'K' . $imageRow;
                $endCell = 'M' . $imageRow;
                $drawing->setCoordinates($startCell);

                $sheet->mergeCells($startCell . ':' . $endCell);
                $drawing->setWorksheet($sheet);
            }
            $imageRow++;
        }

        // Save the spreadsheet as an Excel file
        $excelFilePath = public_path('storage/' . $tableName . '.xlsx');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($excelFilePath);

        // Download the Excel file
        return response()->download($excelFilePath)->deleteFileAfterSend();
    }
}
