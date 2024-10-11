<?php

namespace App\Repositories\Api\Patients;

use App\Models\Patients;
use Carbon\Carbon;

class PatientsRepository
{
    protected Patients $model;

    public function __construct(Patients $model)
    {
        $this->model = $model;
    }

    public function getAll(array $pagination)
    {
        return $this->model::orderBy('id', 'desc')->paginate($pagination['perPage'] ?? 15);
    }

    public function show(int $id)
    {
        return $this->model::where('id', $id)->firstOrFail();
    }

    public function findByNameAndBirth(array $data)
    {
        return $this->model::where(['birth' => $data['birth'], 'name' => $data['name']])->first();
    }

    public function getInternments($id)
    {
        return $this->model::find($id)->internments()->orderBy('id', 'desc')->paginate(5);
    }

    public function getCount()
    {
        return $this->model::count();
    }
}
