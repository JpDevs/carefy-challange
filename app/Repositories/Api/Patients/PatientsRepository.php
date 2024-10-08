<?php

namespace App\Repositories\Api\Patients;

use App\Models\Patients;

class PatientsRepository
{
    protected Patients $model;

    public function __construct(Patients $model)
    {
        $this->model = $model;
    }

    public function getAll(array $pagination)
    {
        return $this->model::paginate($pagination['perPage'] ?? 15);
    }

    public function show(int $id)
    {
        return $this->model::where('id', $id)->firstOrFail();
    }
}
