<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patients extends Model
{
    protected $table = 'patients';

    public $timestamps = true;

    use SoftDeletes;

    protected $fillable = [
        'id',
        'code',
        'name',
        'birth',
    ];

    public function internments()
    {
        return $this->hasMany(Internments::class, 'patient_id', 'id');
    }
}
