<?php

namespace App\Http\Controllers\Api\Statistics;

use App\Http\Controllers\Controller;
use App\Services\Api\Internments\InternmentsService;
use App\Services\Api\Patients\PatientsService;
use App\Services\Api\Drafts\DraftsService;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    protected InternmentsService $internmentsService;
    protected PatientsService $patientsService;
    protected DraftsService $draftsService;

    public function __construct
    (
        InternmentsService $internmentsService,
        PatientsService    $patientsService,
        DraftsService      $draftsService
    )

    {
        $this->internmentsService = $internmentsService;
        $this->patientsService = $patientsService;
        $this->draftsService = $draftsService;
    }

    public function getStatistics(): \Illuminate\Http\JsonResponse
    {
        try {
            $response = [
                'internments' => [
                    'count' => $this->internmentsService->getCount(),
                    'doneCount' => $this->internmentsService->getDoneCount(),
                    'recent' => $this->internmentsService->getAll(['perPage' => 5])
                ],
                'drafts' => $this->draftsService->getCount(),
                'patients' => $this->patientsService->getCount(),
            ];
            return $this->setResponse($response);
        } catch (\Exception $e) {
            return $this->setError($e);
        }
    }
}
