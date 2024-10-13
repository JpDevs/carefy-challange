<?php

namespace App\Services\Api\Patients;

use App\Models\Patients;
use App\Repositories\Api\Patients\PatientsRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        if(isset($pagination['noPaginate'])) {
            return $this->repository->getAllWithoutPagination();
        }
        return $this->repository->getAll($pagination);
    }

    public function show(int $id)
    {
        return $this->repository->show($id);
    }

    public function create(array $validated)
    {
        return DB::transaction(function () use ($validated) {
            $validated['image'] = asset('img/no-image.png');
            return $this->model::create($validated);
        });
    }

    public function update(string $id, array $validated)
    {
        if (!empty($validated['image'])) {
            $validated['image'] = $this->uploadImage($validated['image']);
        }
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
        $birth = Carbon::createFromFormat('d/m/Y', $data['birth']);
        $data['birth'] = $birth->format('Y-m-d');
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

    private function uploadImage($image): string
    {
        $fileName = $image->hashName();
        $image = $image->storeAs('public/patients', $fileName);
        return Storage::url($image);
    }

    public function getCode(array $validated)
    {
        return $this->repository->findByNameAndBirth($validated);
    }

}
