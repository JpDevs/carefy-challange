<?php

namespace App\Services\Api\Drafts;

use App\Models\Drafts;
use App\Repositories\Api\Drafts\DraftsRepository;
use App\Repositories\Api\Internments\InternmentsRepository;
use App\Services\Api\Internments\InternmentsService;
use App\Services\Api\Patients\PatientsService;
use Illuminate\Support\Facades\DB;

class DraftsService
{
    protected DraftsRepository $repository;
    protected InternmentsService $internmentsService;
    protected PatientsService $patientsService;
    protected Drafts $model;

    public function __construct(DraftsRepository $repository, InternmentsService $internmentsService, PatientsService $patientsService, Drafts $model)
    {
        $this->repository = $repository;
        $this->internmentsService = $internmentsService;
        $this->patientsService = $patientsService;
        $this->model = $model;
    }

    /**
     * @throws \Exception
     */
    public function create(array $data)
    {
        if (isset($data['patient_id'])) {
            $patient = $this->patientsService->show($data['patient_id']);
            $validate = $this->internmentsService->validateInternment($data, $patient);
            if ($validate['status'] === false) {
                $data['inconsistencies'] = json_encode($validate['inconsistences']);
            }
        }
        return DB::transaction(function () use ($data) {
            return $this->model::create($data);
        });
    }

    public function getAll(array $validated)
    {
        if (isset($validated['onlyValids']) && (bool)$validated['onlyValids'] === true) {
            return $this->repository->getAllValid();
        }
        return $this->repository->getAll($validated);
    }

    /**
     * @param string $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
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

    /**
     * @throws \Exception
     */
    public function update(int $id, array $validated)
    {
        $validated['inconsistencies'] = null;

        $patient = $this->patientsService->show($validated['patient_id']);
        $validate = $this->internmentsService->validateInternment($validated, $patient);
        if ($validate['status'] === false) {
            $validated['inconsistencies'] = json_encode($validate['inconsistences']);
        }
        return DB::transaction(function () use ($id, $validated) {
            $data = $this->model::where('id', $id)->firstOrFail();
            $data->update($validated);
            return $data;
        });
    }

    public function publish($id)
    {
        $draft = $this->model::where('id', $id)->firstOrFail();

        if (!is_null($draft['inconsistencies']) || is_null($draft['patient_id'])) {
            throw new \Exception('Inconsistencies detected. Please resolve issues and retry');
        }

        $internment = $this->internmentsService->create($draft->toArray());

        return DB::transaction(function () use ($draft, $internment) {
            $draft->delete();
            return $internment;
        });

    }

    public function publishAll()
    {
        $drafts = $this->model::whereNull('inconsistencies')->whereNotNull('patient_id');
        if (empty($drafts->get())) {
            return [];
        }
        $insert = $this->internmentsService->bulkCreate($drafts->get(['patient_id', 'guide', 'entry', 'exit'])->toArray());
        if (!$insert) {
            throw new \Exception('Inconsistencies detected. Please resolve issues and retry');
        }
        return DB::transaction(function () use ($drafts) {
            $output = $drafts->get(['patient_id', 'guide', 'entry', 'exit']);
            $drafts->delete();
            return $output;
        });

    }

    public function getCount()
    {
        return $this->repository->getCount();
    }

    public function truncate()
    {
        $this->model::truncate();
    }
}
