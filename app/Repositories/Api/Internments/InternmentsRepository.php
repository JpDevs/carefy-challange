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
        return $this->model::with('patient')->orderBy('id','desc')->paginate($pagination['perPage'] ?? 15);
    }

    public function show(int $id)
    {
        return $this->model::where('id', $id)->firstOrFail();
    }

    public function findByGuide($guide)
    {
        return $this->model::where('guide', $guide)->first();
    }

    public function intervalHasConflicts($id, $data)
    {
        if (!array_key_exists('entry', $data) || !array_key_exists('exit', $data)) {
            throw new \Exception('Invalid interval data');
        }

        return $this->model::hasConflicts($id, $data['entry'], $data['exit']);

    }
}
