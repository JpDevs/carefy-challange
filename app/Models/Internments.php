<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

    public function scopeIsDeleted($query)
    {
        return $query->withTrashed()->whereNotNull('deleted_at');
    }

    public static function hasConflicts($patientId, $entry, $exit): bool
    {
        $nullExit = self::where('patient_id', $patientId)->where('exit', null)->first();
        if (!empty($nullExit) && $nullExit['entry'] <= $entry) {
            return true;
        }
        $data = self::where('patient_id', $patientId)->where(function (Builder $query) use ($entry, $exit) {
            if (!empty($exit)) {
                $query->whereBetween('entry', [$entry, $exit])
                    ->orWhereBetween('exit', [$entry, $exit])
                    ->orWhere(function ($query) use ($entry, $exit) {
                        $query->where('entry', '<=', $entry)->where('exit', '>=', $exit);
                    });
            } else {
                $query->where('entry', '<=', $entry);
            }
        })->orderBy('id', 'desc')->first();

        if (!empty($data)) {
            return $data['exit'] > $entry ?? false;
        }
        return false;
    }
}
