<?php

namespace App\Services\Api\Census;

use App\Services\Api\Internments\InternmentsService;
use App\Services\Api\Drafts\DraftsService;
use App\Services\Api\Patients\PatientsService;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;

class CensusService
{
    protected PatientsService $patientsService;
    protected DraftsService $draftsService;
    protected InternmentsService $internmentsService;

    public function __construct
    (
        PatientsService       $patientsService,
        DraftsService         $draftsService,
        InternmentsService $internmentsService
    )

    {
        $this->patientsService = $patientsService;
        $this->draftsService = $draftsService;
        $this->internmentsService = $internmentsService;
    }

    /**
     * @throws \Exception
     */
    public function uploadFile(UploadedFile $file)
    {
        $data = $this->mountData($file);
        $drafts = [];
        foreach ($data as $key => $row) {
            $draft = [];
            $patient = [
                'code' => $row['codigo'],
                'name' => $row['nome'],
                'birth' => $row['nascimento'],
            ];

            $internment = [
                'guide' => $row['guia'],
                'entry' => $this->parseDate($row['entrada']),
                'exit' => $this->parseDate($row['saida'])
            ];

            $patientValidation = $this->patientsService->validatePatient($patient);

            if ($patientValidation['status'] === false) {
                $draft['inconsistencies']['patient'] = $patientValidation['inconsistence'];
                $draft['patient_data'] = json_encode($patient);
            } else {
                $patientRow = $this->patientsService->findByNameAndBirth($patient);
                if (empty($patientRow)) {
                    $patient['birth'] = $this->parseDate($patient['birth']);
                    $patient = $this->patientsService->create($patient);
                } else {
                    $patient = $patientRow;
                }
                $draft['patient_id'] = $patient['id'];
            }

            $internmentValidation = $this->internmentsService->validateInternment($internment, $patient);
            if ($internmentValidation['status'] === false) {
                $draft['inconsistencies']['internment'] = $internmentValidation['inconsistences'];
            }

            if (isset($draft['inconsistencies'])) {
                $draft['inconsistencies'] = json_encode($draft['inconsistencies']);
            }
            $draft = $draft + $internment;
            $drafts[] = $this->persistDraft($draft);
        }
        return $drafts;
    }

    private function mountData(UploadedFile $file): array
    {
        $path = $file->getRealPath();
        $data = array_map('str_getcsv', file($path));
        $keys = $data[0];

        unset($data[0]);
        $output = [];

        foreach ($data as $row) {
            $output[] = array_combine($keys, $row);
        }

        return $output;
    }

    private function persistDraft(array $data)
    {
        return $this->draftsService->create($data);
    }

    private function parseDate(string $date): string
    {
        $output = Carbon::createFromFormat('d/m/Y', $date);
        $output = $output->format('Y-m-d');
        return $output;
    }
}
