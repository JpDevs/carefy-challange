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
        if (!isset($validated['patient_id'])) {
            throw new \Exception('Missing patient_id');
        }
        $validated['inconsistencies'] = null;
        $patient = $this->patientsService->show($validated['patient_id']);
        $validateInternment = $this->internmentsService->validateInternment($validated, $patient);
        if ($validateInternment['status'] === false) {
            $validated['inconsistencies'] = json_encode(['internment' => $validateInternment['inconsistences']]);
        }
        $validated['patient_data'] = null;
        return $this->put($id, $validated);
    }

    public function put($id, $validated)
    {
        return DB::transaction(function () use ($id, $validated) {
            $data = $this->model::where('id', $id)->firstOrFail();
            $data->update($validated);
            return $data;
        });
    }

    /**
     * @throws \Exception
     */
    public function publish($id)
    {
        $draft = $this->model::where('id', $id)->firstOrFail();

        if (!is_null($draft['inconsistencies']) || is_null($draft['patient_id'])) {
            throw new \Exception('Inconsistencies detected. Please resolve issues and retry');
        }
        $internment = $this->internmentsService->create($draft->toArray());

        $output = DB::transaction(function () use ($draft, $internment) {
            $draft->delete();
            return $internment;
        });
        $this->revalidateGuide($internment['guide']);

        return $output;

    }

    /**
     * @throws \Exception
     */
    public function publishAll()
    {
        $drafts = $this->model::whereNull('inconsistencies')->whereNotNull('patient_id');
        $guides = $drafts->get()->pluck('guide')->toArray();
        if (empty($drafts->get())) {
            return [];
        }
        $insert = $this->internmentsService->bulkCreate($drafts->get(['patient_id', 'guide', 'entry', 'exit'])->toArray());

        if (!$insert) {
            throw new \Exception('Inconsistencies detected. Please resolve issues and retry');
        }
        $output = DB::transaction(function () use ($drafts) {
            $output = $drafts->get(['patient_id', 'guide', 'entry', 'exit']);
            $drafts->delete();
            return $output;
        });
        $this->bulkRevalidateGuide($guides);
        return $output;

    }


    /**
     * @throws \Exception
     */
    public function revalidateGuide($guide)
    {
        $draft = $this->repository->findByGuide($guide);
        if (!is_null($draft)) {
            return $this->addRepeated($draft);
        }
        return null;
    }

    /**
     * @throws \Exception
     */
    public function bulkRevalidateGuide($guides): array
    {
        $output = [];
        foreach ($guides as $key => $guide) {
            $output[$key] = $this->revalidateGuide($guide);
            if (is_null($output[$key])) {
                unset($output[$key]);
            }
        }
        return $output;
    }

    public function addRepeated($draft)
    {
        $inconsistencies = json_decode($draft['inconsistencies'], true);
        $inconsistencies['internment'][] = 'sameGuide';
        $draft['inconsistencies'] = json_encode($inconsistencies);
        return $this->put($draft['id'], $draft->toArray());
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
