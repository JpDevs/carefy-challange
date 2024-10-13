<?php

namespace App\Http\Controllers\Api\Patients;

use App\Http\Controllers\Controller;
use App\Services\Api\Patients\PatientsService;
use Illuminate\Http\Request;

class PatientsController extends Controller
{
    protected PatientsService $service;

    protected array $rules = [
        'code' => ['required', 'string'],
        'noPaginate' => ['nullable','boolean'],
        'name' => ['required', 'string'],
        'birth' => ['required', 'date'],
        'image' => ['image', 'mimes:jpeg,jpg,png,gif','nullable'],
    ];

    public function __construct(PatientsService $service)
    {
        $this->service = $service;
    }


    /**
     * Display a listing of the resource.
     */
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

    public function getInternments($id): \Illuminate\Http\JsonResponse
    {
        try {
            $response = $this->service->getInternments($id);
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

    public function getCode(Request $request)
    {
        try {
            $rules = [
                'birth' => ['required','date'],
                'name' => ['required', 'string'],
            ];
            $validated = $this->validated($rules,$request->all());
            $response = $this->service->getCode($validated);
            return $this->setResponse($response);
        } catch (\Exception $e) {
            return $this->setError($e);
        }
    }
}
