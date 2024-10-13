<?php

namespace App\Http\Controllers\Api\Drafts;

use App\Http\Controllers\Controller;
use App\Services\Api\Drafts\DraftsService;
use App\Services\Api\Patients\PatientsService;
use Illuminate\Http\Request;

class DraftsController extends Controller
{

    protected DraftsService $service;
    protected PatientsService $patientsService;

    protected array $rules = [
        'patient_id' => ['integer'],
        'guide' => ['string', 'required'],
        'entry' => ['date', 'required'],
        'exit' => ['date', 'nullable'],
    ];

    public function __construct(DraftsService $service, PatientsService $patientsService)
    {
        $this->service = $service;
        $this->patientsService = $patientsService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $rules = ['perPage' => 'integer', 'onlyValids' => ['nullable', 'boolean']];
            $validated = $this->validated($rules, $request->all());
            $response = $this->service->getAll($validated);
            return $this->setResponse($response);
        } catch (\Exception $e) {
            return $this->setError($e);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $rules = ['perPage' => 'integer'];
            $validated = $this->validated($rules, $request->all());
            $response['patients'] = $this->patientsService->getAll($validated);
            return $this->setResponse($response);
        } catch (\Exception $e) {
            return $this->setError($e);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validated = $this->validated();
            $response = $this->service->create($validated);
            return $this->setResponse($response, 201);
        } catch (\Exception $e) {
            return $this->setError($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): \Illuminate\Http\JsonResponse
    {
        try {
            $response = $this->service->show($id);
            return $this->setResponse($response);
        } catch (\Exception $e) {
            return $this->setError($e);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $rules = ['perPage' => 'integer'];
            $validated = $this->validated($rules, $request->all());

            $response['draft'] = $this->service->show($id);
            $response['patients'] = $this->patientsService->getAll($validated);
            return $this->setResponse($response);
        } catch (\Exception $e) {
            return $this->setError($e);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): \Illuminate\Http\JsonResponse
    {
        try {
            $validated = $this->validated();
            $response = $this->service->update($id, $validated);
            return $this->setResponse($response);
        } catch (\Exception $e) {
            return $this->setError($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): \Illuminate\Http\JsonResponse
    {
        try {
            $response = $this->service->delete($id);
            return $this->setResponse($response, 204);
        } catch (\Exception $e) {
            return $this->setError($e);
        }
    }

    public function publish($id): \Illuminate\Http\JsonResponse
    {
        try {
            $response = $this->service->publish($id);
            return $this->setResponse($response, 201);
        } catch (\Exception $e) {
            return $this->setError($e);
        }
    }

    public function publishAll()
    {
        try {
            $response = $this->service->publishAll();
            return $this->setResponse($response, 201);
        } catch (\Exception $e) {
            return $this->setError($e);
        }
    }
}
