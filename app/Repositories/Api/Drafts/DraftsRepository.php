<?php

namespace App\Repositories\Api\Drafts;

use App\Models\Drafts;

class DraftsRepository
{
    protected Drafts $model;

    public function __construct(Drafts $model)
    {
        $this->model = $model;
    }

    public function getAll(array $pagination)
    {
        return $this->model::with('patient')->orderBy('id', 'desc')->paginate($pagination['perPage'] ?? 15);
    }

    public function show(string $id)
    {
        return $this->model::with('patient')->where('id', $id)->firstOrFail();
    }

    public function getCount()
    {
        return $this->model::count();
    }
}
