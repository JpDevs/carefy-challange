var validPatient = true;
var birth = null;
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
    $('#draftForm').on('submit', function (event) {
        Swal.fire({
            title: 'Aguarde...',
            html: '<div class="spinner-border text-primary mb-2" role="status"><span class="sr-only">Loading...</span></div>',
            showConfirmButton: false,
            allowOutsideClick: false,
            onOpen: () => {
                Swal.showLoading()
            }
        });
        if (!validPatient) {
            Swal.fire(
                'Erro!',
                'Paciente Inválido. Corrija o código para salvar.',
                'error'
            )
        }
        let formData = new FormData($("form[name='draftForm']")[0])
        formData.append('_method', 'PUT')

        $.ajax({
            url: draftRoute + '/' + draftId,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                Swal.hideLoading()
                updateData();
                if(data.inconsistencies !== null) {
                    Swal.fire(
                        'Aviso: Novas incongruencias!',
                        'Por favor corrija as inconsistências para confirmar a internação',
                        'warning'
                    )
                } else {
                    Swal.fire({
                        title: 'Rascunho atualizado!',
                        text: 'Deseja confirmar a internação?',
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Confirmar',
                        cancelButtonText: 'Não Confirmar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Aguarde...',
                                html: '<div class="spinner-border text-primary mb-2" role="status"><span class="sr-only">Loading...</span></div>',
                                showConfirmButton: false,
                                allowOutsideClick: false,
                                onOpen: () => {
                                    Swal.showLoading()
                                }
                            });
                            saveDraft(draftId);
                        }
                    })
                }
            }
        })
    })
})

function generateCode() {
    let code = Math.floor(100000 + Math.random() * 900000);
    $('#guide').val(code);
    $('#guide').removeClass('is-invalid');
}

function updateData() {
    $('#entryDate').removeClass('is-invalid');
    $('#exitDate').removeClass('is-invalid');
    $('#guide').removeClass('is-invalid');
    $.ajax({
        url: statisticsRoute,
        type: 'GET',
        success: function (data) {
            if (data.patient_data !== null && data.patient_id === null) {
                data.patient = JSON.parse(data.patient_data)
                validPatient = false;
            }

            if (!validPatient) {
                $('#codeLabel').attr('style', 'display: none !important');
                $('#patientCode').addClass('is-invalid');
                $('#fixCode').show();
                $('#patientCode').val(data.patient.code)

            }

            let incosistencies = data?.inconsistencies ? JSON.parse(data.inconsistencies) : null;
            let internmentInconsistences = incosistencies?.internment ? incosistencies.internment : null


            let haveIntervalConflicts = false;

            let internmentActions = {
                intervalConflicts: function () {
                    haveIntervalConflicts = true;
                    $('#entryDate').addClass('is-invalid');
                    $('#entryMessage').html('Intervalo de internamento inválido. Verifique se não há internações marcadas para este intervalo e tente novamente.');
                    $('#exitDate').addClass('is-invalid');
                    $('#exitMessage').html('Intervalo de internamento inválido. Verifique se não há internações marcadas para este intervalo e tente novamente.');

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
                    $('#guideMessage').html('Guia de internação já existente no sistema.');
                },
            }

            if (internmentInconsistences !== null) {
                Object.values(internmentInconsistences).forEach(value => {
                    internmentActions[value]();
                });
            }


            patientName = data.patient.name;
            birth = data.patient.birth;
            let birthDate = new Date(birth + 'T00:00:00');

            $('#patientName').html(patientName)
            $('#birthDate').html(birthDate.toLocaleDateString('pt-BR'))
            $('#patientId').val(data.patient.id)
            $('#codeLabel').html(data.patient.code)
            $('#patientImg').attr('src', data.patient.image)
            $('#patientCard').show()

            $('#entryDate').val(data.entry)
            $('#exitDate').val(data.exit)
            $('#guide').val(data.guide)
            $('#preLoader').attr('style', 'display: none !important');
        },
        error: function (error) {
            $('#errorMessage').show();
            $('#preLoader').attr('style', 'display: none !important');
        }
    });
}

function fixCode() {
    let name = patientName;
    $.ajax({
        url: codeRoute,
        type: 'GET',
        data: {
            birth: birth,
            name: name
        },
        success: function (response) {
            $('#patientCode').val(response.code)
            $('#codeLabel').html(response.code);
            $('#patientId').val(response.id)
            $('#patientCode').removeClass('is-invalid')
            $('#patientCode').addClass('is-valid')
            $('#codeAction').attr('style', 'display: none !important');
            validPatient = true;
        }
    });
}

function saveDraft(id) {
    $.ajax({
        url: draftRoute + '/' + id + '/publish',
        type: 'POST',
        success: function (response) {
            Swal.hideLoading()
            Swal.fire({
                title: 'Sucesso!',
                text: 'Internação confirmada com sucesso!',
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Visualizar internaçao',
                cancelButtonText: 'Voltar'
            }).then((result) => {
                if (result.value) {
                    window.location.href = internments + '/' + response.id;
                } else {
                    window.location.href = redirectRoute;
                }
            });
        }
    })
}
