<?php

namespace App\Repositories\Api\Internments;

use App\Models\Internments;

class InternmentsRepository
{
    protected Internments $model;

    public function __construct(Internments $model)
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
