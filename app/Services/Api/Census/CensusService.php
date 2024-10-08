<?php

namespace App\Services\Api\Census;

use App\Models\Drafts;
use App\Models\Patients;
use App\Repositories\Api\Census\CensusRepository;
use App\Repositories\Api\Internments\InternmentsRepository;
use App\Repositories\Api\Patients\PatientsRepository;
use App\Services\Api\Patients\PatientsService;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;

class CensusService
{
    protected CensusRepository $repository;
    protected PatientsService $patientsService;
    protected InternmentsRepository $internmentsRepository;
    protected Drafts $draftsModel;

    public function __construct
    (
        CensusRepository      $repository,
        Drafts                $draftsModel,
        PatientsService       $patientsService,
        InternmentsRepository $internmentsRepository
    )

    {
        $this->repository = $repository;
        $this->draftsModel = $draftsModel;
        $this->patientsService = $patientsService;
        $this->internmentsRepository = $internmentsRepository;
    }

    public function uploadFile(UploadedFile $file)
    {
        $data = $this->mountData($file);
        $inconsistencies = [];
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

            $patientValidation = $this->validatePatient($patient);

            if ($patientValidation['status'] === false) {
                $draft['inconsistencies']['patient'] = $patientValidation['inconsistence'];
                $draft['patient_data'] = json_encode($patient);
            } else {
                $patientRow = $this->searchPatientByNameAndBirth($patient);
                if (empty($patientRow)) {
                    $patient['birth'] = $this->parseDate($patient['birth']);
                    $patient = $this->patientsService->create($patient);
                } else {
                    $patient = $patientRow;
                }
                $draft['patient_id'] = $patient['id'];
            }

            $internmentValidation = $this->validateInternment($internment, $patient);
            if ($internmentValidation['status'] === false) {
                $draft['inconsistencies']['internment'] = $internmentValidation['inconsistencies'];
            }

            $draft = $this->mountDraft($draft, $patient, $internment);

            return $this->persistDraft($draft);

        }
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

    private function mountDraft($draft, $patient, $internment)
    {

    }

    private function persistDraft()
    {
        
    }

    private function searchPatientByNameAndBirth($data)
    {
        return $this->patientsService->findByNameAndBirth($data);
    }

    private function validatePatient(array $data)
    {
        $patient = $this->searchPatientByNameAndBirth($data);
        if (empty($patient)) {
            return [
                'status' => true
            ];
        }
        if ($patient['code'] != $data['code']) {
            return [
                'status' => false,
                'inconsistence' => ['patientCode']
            ];
        }

        return [
            'status' => true,
        ];
    }

    private function parseDate(string $date)
    {
        $output = Carbon::createFromFormat('d/m/Y', $date);
        $output = $output->format('Y-m-d');
        return $output;
    }

    private function validateInternment(array $internment, $patient): array
    {
        $inconsistences = [];

        $sameGuide = !empty($this->internmentsRepository->findByGuide($internment['guide']));

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
            $hasConflict = $this->internmentsRepository->intervalHasConflicts($patient['id'], $internment);
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
}
