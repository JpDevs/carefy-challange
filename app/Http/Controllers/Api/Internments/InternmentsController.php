<?php

namespace App\Http\Controllers\Api\Internments;

use App\Http\Controllers\Controller;
use App\Services\Api\Internments\InternmentsService;
use Illuminate\Http\Request;

class InternmentsController extends Controller
{
    protected InternmentsService $service;

    protected $rules = [
        'patient_id' => ['required','integer'],
        'guide' => ['required','string'],
        'entry' => ['required','date'],
        'exit' => ['required','date'],
    ];

    public function __construct(InternmentsService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return [];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $this->validated();
            $response = $this->service->create($validated);
            return $this->setResponse($response);
        } catch (\Exception $e) {
            return $this->setError($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
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
    public function edit(string $id)
    {
        return [];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
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
    public function destroy(string $id)
    {
        try {
            $response = $this->service->delete($id);
            return $this->setResponse($response);
        } catch (\Exception $e) {
            return $this->setError($e);
        }
    }
}
