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
        return $this->model::with('patient')->orderBy('id', 'desc')->paginate($pagination['perPage'] ?? 15);
    }

    public function show(int $id)
    {
        return $this->model::with('patient')->where('id', $id)->firstOrFail();
    }

    public function findByGuide($guide)
    {
        return $this->model::where('guide', $guide)->first();
    }

    public function intervalHasConflicts($id, $data)
    {
        return $this->model::hasConflicts($id, $data['entry'], $data['exit']);

    }

    public function getCount()
    {
        return $this->model::where('exit', '<', now())->orWhere('exit', null)->count();
    }

    public function getDoneCount()
    {
        return $this->model::where('exit', '>=', now())->count();
    }

    public function trash(array $pagination)
    {
        return $this->model::isDeleted()->orderBy('id', 'desc')->paginate($pagination['perPage'] ?? 15);
    }
}
