$(document).ready(function () {
    updateData();
    $('#entryDate').on('change', function () {
        $('#entryDate').removeClass('is-invalid');
    })
    $('#exitDate').on('change', function () {
        $('#exitDate').removeClass('is-invalid');
    })
    $('#guide').on('change', function () {
        $('#guide').removeClass('is-invalid');
    })
})

function generateCode(type) {
    let code = Math.floor(100000 + Math.random() * 900000);
    if (type === 'patient') {
        $('#patientCode').val(code);
    }
    if (type === 'guide') {
        $('#guide').val(code);
        $('#guide').removeClass('is-invalid');
    }
}

function updateData() {
    $.ajax({
        url: statisticsRoute,
        type: 'GET',
        success: function (data) {
            let validPatient = true;
            if (data.patient_data !== null && data.patient_id === null) {
                data.patient = JSON.parse(data.patient_data)
                validPatient = false;
            }

            if(!validPatient) {
               $('#codeLabel').hide();
               $('#patientCode').addClass('is-invalid');
               $('#codeAction').html('Corrigir Código')
               $('#codeAction').attr('onclick', 'fixCode()')
            }

            let incosistencies = data?.inconsistencies ? JSON.parse(data.inconsistencies) : null;
            let internmentInconsistences = incosistencies?.internment ? incosistencies.internment : null

            let haveIntervalConflicts = false;

            let internmentActions = {
                intervalConflicts: function () {
                    haveIntervalConflicts = true;
                    $('#entryDate').addClass('is-invalid');
                    $('#entryMessage').html('Intervalo de internamento inválido');
                    $('#exitDate').addClass('is-invalid');
                    $('#exitMessage').html('Intervalo de internamento inválido');

                },
                entryMinorBirth: function () {
                    $('#entryDate').addClass('is-invalid');
                    if (haveIntervalConflicts) {
                        $('#entryMessage').append('<br> Data de Entrada menor que a data de nascimento do paciente');
                    } else {
                        $('#entryMessage').html('Data de Entrada menor que a data de nascimento do paciente');
                    }
                },
                exitMinorEqualEntry: function () {
                    $('#exitDate').addClass('is-invalid');
                    if (haveIntervalConflicts) {
                        $('#exitMessage').append('<br> Data de Saída menor ou igual a data de entrada');
                    } else {
                        $('#exitMessage').html('Data de Saída menor que a data de entrada');
                    }
                },
                sameGuide: function () {
                    $('#guide').addClass('is-invalid');
                    $('#guideMessage').html('Guia de internação já existente no sistema');
                },
            }

            if (internmentInconsistences !== null) {
                Object.values(internmentInconsistences).forEach(values => {
                    internmentActions[values]();
                });
            }



            patientName = data.patient.name ?? 'nao'

            $('#patientName').val(patientName)
            $('#birthDate').val(data.patient.birth)
            $('#entryDate').val(data.entry)
            $('#exitDate').val(data.exit)
            $('#guide').val(data.guide)
            $('#patientCode').val(data.patient.code)
            $('#preLoader').attr('style', 'display: none !important');
            $('#patientCard').show()
            $('#patientImg').attr('src', data.patient.image)
        },
        error: function (error) {
            $('#errorMessage').show();
            $('#preLoader').attr('style', 'display: none !important');
        }
    });
}

function fixCode() {
    let birthDate = $('#birthDate').val();
    let name = $('#name').val();
}
