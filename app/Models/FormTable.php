<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormTable extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function getNameAttribute($value)
    {
        // Modify the attribute value if needed
        return ucfirst($value); // Example: Capitalize the name
    }
}
