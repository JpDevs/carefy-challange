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

    /**
     * @throws \Exception
     */
    public function validateInternment(array $internment, $patient): array
    {
        $inconsistences = [];

        $sameGuide = !empty($this->findByGuide($internment['guide']));

        if ($sameGuide) {
            $inconsistences[] = 'sameGuide';
        }

        if ($internment['entry'] < $patient['birth']) {
            $inconsistences[] = 'entryMinorBirth';
        };

        if ($internment['exit'] <= $internment['entry']) {
            $inconsistences[] = 'exitMinorEqualEntry';
        }

        if (isset($patient['id'])) {
            $hasConflict = $this->intervalHasConflicts($patient['id'], $internment);
            if ($hasConflict) {
                $inconsistences[] = 'intervalConflicts';
            }
        }

        if (empty($inconsistences)) {
            return [
                'status' => true
            ];
        }

        return [
            'status' => false,
            'inconsistences' => $inconsistences
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

    public function findByGuide($guide)
    {
        return $this->repository->findByGuide($guide);
    }

    /**
     * @throws \Exception
     */
    public function intervalHasConflicts($id, $data)
    {
        if (!array_key_exists('entry', $data) || !array_key_exists('exit', $data)) {
            throw new \Exception('Invalid interval data');
        }
        return $this->repository->intervalHasConflicts($id, $data);
    }

    public function create(array $validated)
    {
        if (isset($validated['id'])) {
            unset($validated['id']);
        }
        return DB::transaction(function () use ($validated) {
            return $this->model::create($validated);
        });
    }

    public function bulkCreate(array $validated)
    {
        return DB::transaction(function () use ($validated) {
            return $this->model::insert($validated);
        });
    }

    public function update(string $id, array $validated)
    {
        unset($validated['patient_id']);
        return DB::transaction(function () use ($id, $validated) {
            return $this->model::where('id', $id)->firstOrFail()->update($validated);
        });
    }

    public function delete(string $id)
    {
        return DB::transaction(function () use ($id) {
            return $this->model::where('id', $id)->firstOrFail()->delete();
        });
    }

    public function getCount()
    {
        return $this->repository->getCount();
    }

    public function getDoneCount()
    {
        return $this->repository->getDoneCount();
    }

    public function trash(array $pagination)
    {
        return $this->repository->trash($pagination);
    }

    public function cleanTrash()
    {
        return DB::transaction(function () {
            return $this->model::isDeleted()->forceDelete();
        });
    }

    public function destroyTrash($id)
    {
        return DB::transaction(function () use ($id) {
            return $this->model::withTrashed()->where('id', $id)->firstOrFail()->forceDelete();
        });
    }

    public function restoreTrash($id)
    {
        return DB::transaction(function () use ($id) {
            return $this->model::withTrashed()->where('id', $id)->firstOrFail()->restore();
        });
    }
}
