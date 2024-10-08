<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drafts extends Model
{
    protected $table = 'drafts';

    public $timestamps = true;

    protected $fillable = [
        'id',
        'patient_id',
        'patient_data',
        'guide',
        'entry',
        'exit',
        'inconsistencies',
    ];

    public function patient()
    {
        return $this->belongsTo(Patients::class, 'patient_id', 'id');
    }
}
