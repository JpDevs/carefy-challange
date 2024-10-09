<?php

namespace App\Services\Api\Drafts;

use App\Models\Drafts;
use App\Repositories\Api\Drafts\DraftsRepository;
use Illuminate\Support\Facades\DB;

class DraftsService
{
    protected DraftsRepository $repository;
    protected Drafts $model;

    public function __construct(DraftsRepository $repository, Drafts $model)
    {
        $this->repository = $repository;
        $this->model = $model;
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return $this->model::create($data);
        });
    }

    public function getAll(array $validated)
    {
        return $this->repository->getAll($validated);
    }

    public function show(string $id)
    {
        return $this->repository->show($id);
    }

    public function delete(string $id)
    {
        return DB::transaction(function () use ($id) {
            return $this->model::where('id', $id)->firstOrFail()->delete();
        });
    }

    public function update(int $id, array $validated)
    {
        return DB::transaction(function () use ($id, $validated) {
            return $this->model::where('id', $id)->firstOrFail()->update($validated);
        });
    }
}
