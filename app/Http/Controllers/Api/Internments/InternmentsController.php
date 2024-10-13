<?php

namespace App\Http\Controllers\Api\Internments;

use App\Http\Controllers\Controller;
use App\Services\Api\Internments\InternmentsService;
use Illuminate\Http\Request;

class InternmentsController extends Controller
{
    protected InternmentsService $service;

    protected array $rules = [
        'patient_id' => ['required', 'integer'],
        'guide' => ['required', 'string'],
        'entry' => ['required', 'date'],
        'exit' => ['required', 'date'],
    ];

    public function __construct(InternmentsService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $rules = ['perPage' => 'integer'];
            $validated = $this->validated($rules, $request->all());
            $response = $this->service->getAll($validated);
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
            $response = $this->service->manualCreate($validated);
            return $this->setResponse($response, 201);
        } catch (\Exception $e) {
            return $this->setError($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $response = $this->service->show($id);
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

    public function trash(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $rules = ['perPage' => 'integer'];
            $validated = $this->validated($rules, $request->all());
            $response = $this->service->trash($validated);
            return $this->setResponse($response);
        } catch (\Exception $e) {
            return $this->setError($e);
        }
    }

    public function cleanTrash(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $response = $this->service->cleanTrash();
            return $this->setResponse($response, 204);
        } catch (\Exception $e) {
            return $this->setError($e);
        }
    }

    public function destroyTrash($id): \Illuminate\Http\JsonResponse
    {
        try {
            $response = $this->service->destroyTrash($id);
            return $this->setResponse($response, 204);
        } catch (\Exception $e) {
            return $this->setError($e);
        }
    }

    public function restoreTrash($id)
    {
        try {
            $response = $this->service->restoreTrash($id);
            return $this->setResponse($response);
        } catch (\Exception $e) {
            return $this->setError($e);
        }
    }
}
