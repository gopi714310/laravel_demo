<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDynamicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($tableName, function (Blueprint $table) use ($columnNames, $columnTypes) {
            $table->id();

            for ($i = 0; $i < count($columnNames); $i++) {
                $columnName = $columnNames[$i];
                $columnType = $columnTypes[$i];

                switch ($columnType) {
                    case 'string':
                        $table->string($columnName);
                        break;
                    case 'integer':
                        $table->integer($columnName);
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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dynamic');
    }
}
