<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentNote extends Model
{
    use HasFactory;
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $fillable = [
        'incident_id',
        'user_id',
        'note'
    ];
    public function incident(){
        return $this->belongsTo(Incident::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

}
