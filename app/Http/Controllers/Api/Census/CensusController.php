<?php

namespace App\Http\Controllers\Api\Census;

use App\Http\Controllers\Controller;
use App\Services\Api\Census\CensusService;
use Illuminate\Http\Request;

class CensusController extends Controller
{
    protected CensusService $service;
    protected $rules = [
        'file' => ['required', 'file', 'mimes:csv']
    ];

    public function __construct(CensusService $service)
    {
        $this->service = $service;
    }


    public function uploadFile(Request $request)
    {
        try {
            $validated = $this->validated();
            $response = $this->service->uploadFile($validated['file']);
            return $this->setResponse($response, 201);
        } catch (\Exception $e) {
            return $this->setError($e);
        }
    }

}
