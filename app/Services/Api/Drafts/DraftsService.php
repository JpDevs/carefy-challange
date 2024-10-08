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
}
