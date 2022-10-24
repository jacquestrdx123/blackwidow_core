<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'institution_id',
        'description'
    ];
    public function institutions()
    {
        return $this->belongsToMany(Institution::class, 'department_institution');
    }
}
