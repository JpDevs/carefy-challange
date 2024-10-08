<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Internments extends Model
{
    protected $table = 'internments';

    public $timestamps = true;

    use SoftDeletes;

    protected $fillable = [
        'id',
        'patient_id',
        'guide',
        'entry',
        'exit',
    ];

    public function patient(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Patients::class, 'patient_id', 'id');
    }
}
