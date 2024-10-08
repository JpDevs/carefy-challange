<?php

namespace App\Services\Api\Internments;

use App\Models\Internments;
use App\Repositories\Api\Internments\InternmentsRepository;
use Illuminate\Support\Facades\DB;

class InternmentsService
{
    protected InternmentsRepository $repository;
    protected Internments $model;

    public function __construct(InternmentsRepository $repository, Internments $model)
    {
        $this->repository = $repository;
        $this->model = $model;
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
            return $this->model::where('id', $id)->firstOrFail()->update($validated);
        });
    }

    public function delete(string $id)
    {
        return DB::transaction(function () use ($id) {
            return $this->model::where('id',$id)->firstOrFail()->delete();
        });
    }
}
