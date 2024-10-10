<?php

namespace App\Services\Api\Patients;

use App\Models\Patients;
use App\Repositories\Api\Patients\PatientsRepository;
use Illuminate\Support\Facades\DB;

class PatientsService
{
    protected PatientsRepository $repository;
    protected Patients $model;

    public function __construct(PatientsRepository $repository, Patients $model)
    {
        $this->repository = $repository;
        $this->model = $model;
    }

    public function validatePatient(array $data): array
    {
        $patient = $this->findByNameAndBirth($data);
        if (empty($patient)) {
            return [
                'status' => true
            ];
        }
        if ($patient['code'] != $data['code']) {
            return [
                'status' => false,
                'inconsistence' => ['patientCode']
            ];
        }

        return [
            'status' => true,
        ];
    }

    public function getAll(array $pagination)
    {
        return $this->repository->getAll($pagination);
    }

    public function show(int $id)
    {
        return $this->repository->show($id);
    }

    public function create(array $validated)
    {
        return DB::transaction(function () use ($validated) {
            return $this->model::create($validated);
        });
    }

    public function update(string $id, array $validated)
    {
        return DB::transaction(function () use ($id, $validated) {
            $data = $this->model::where('id', $id)->firstOrFail();
            $data->update($validated);
            return $data;
        });
    }

    public function delete(string $id)
    {
        return DB::transaction(function () use ($id) {
            return $this->model::where('id', $id)->firstOrFail()->delete();
        });
    }

    public function findByNameAndBirth(array $data)
    {
        return $this->repository->findByNameAndBirth($data);
    }

    public function getInternments($id)
    {
        return $this->repository->getInternments($id);
    }

    public function getCount()
    {
        return $this->repository->getCount();
    }

}
