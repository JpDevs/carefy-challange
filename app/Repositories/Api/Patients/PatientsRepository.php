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
        return $this->model::paginate($pagination['perPage'] ?? 15);
    }

    public function show(int $id)
    {
        return $this->model::where('id', $id)->firstOrFail();
    }

    public function findByNameAndBirth(array $data)
    {
        $birth = Carbon::createFromFormat('d/m/Y', $data['birth']);
        $birth = $birth->format('Y-m-d');
        $name = $data['name'];

        return $this->model::where(['birth' => $birth, 'name' => $name])->first();
    }
}
