<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;




class FormController extends Controller
{

    public function showTable($tableName)
    {

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

        return view('add_form_table', compact('tableName', 'inputs'));
    }

    public function saveForm(Request $request)
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
        $data['id'] = null;
        $data['created_at'] = Carbon::now();
        $data['updated_at'] = Carbon::now();

        DB::table($tableName)->insert($data);


        return redirect()->route('columns.show', ['tableName' => $tableName])->with('success', 'Data added successfully!');
    }

    // public function showTableData($tableName)
    // {
    //     $columns = Schema::getColumnListing($tableName);
    //     $columns = array_diff($columns, ['id', 'created_at', 'updated_at']);

    //     $data = DB::table($tableName)->get($columns);

    //     return view('view_table_col', compact('data', 'columns'));
    // }
}
