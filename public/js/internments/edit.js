let patientName = null;
let validationMessages = {
    sameGuide: 'Você já cadastrou esse guia de internação. Por favor, faça a atualização.',
    entryMinorBirth: 'A data de entrada que você inseriu é inválida.',
    exitMinorEqualEntry: 'As datas de entrada e saída não estão corretas. Por favor, verifique e tente novamente.',
    intervalConflicts: 'Já existem internações registradas para este paciente nesse período.'
}

$(document).ready(function () {
    updateData()
    $('#internmentForm').on('submit', function (event) {
        let entryDate = new Date($('#entryDate').val() + 'T00:00:00');
        let exitDate = new Date($('#exitDate').val() + 'T00:00:00');
        if (exitDate < entryDate) {
            Swal.fire(
                'Erro!',
                'Data de saida deve ser maior que a de entrada',
                'error'
            )
            return false
        } else if (exitDate < birthDate) {
            Swal.fire(
                'Erro!',
                'Data de saida deve ser maior que a data de nascimento do paciente',
                'error'
            )
            return false
        }
        if ($('#patients').val() === '' || $('#entryDate').val() === '') {
            Swal.fire(
                'Erro!',
                'Preencha todos os dados',
                'error'
            )
            return false
        }
        Swal.fire({
            title: 'Aguarde...',
            html: '<div class="spinner-border text-primary mb-2" role="status"><span class="sr-only">Loading...</span></div>',
            showConfirmButton: false,
            allowOutsideClick: false,
            onOpen: () => {
                Swal.showLoading()
            }
        });

        if (!$('#entryDate').val() || !$('#guide').val()) {
            Swal.fire(
                'Erro!',
                'Preencha todos os campos',
                'error'
            )
            return false;
        }

        let formData = new FormData($("form[name='internmentForm']")[0])
        formData.append('_method', 'PUT')
        formData.append('patient_id', patientId)
        $.ajax({
            url: statisticsRoute,
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                Swal.hideLoading()
                Swal.fire(
                    'Sucesso!',
                    'Internação atualizada com sucesso',
                    'success'
                ).then(function () {
                    updateData()
                })
            },
            error: function (error) {
                Swal.fire(
                    'Erro!',
                    'Erro ao atualizar internação: ' + error.responseJSON.message,
                    'error'
                );
            }
        });
    });
});

function updateData() {
    $.ajax({
        url: statisticsRoute,
        type: 'GET',
        success: function (data) {
            let birthDate = new Date(data.patient.birth + 'T00:00:00');
            $('#patientName').html(data.patient.name)
            $('#birthDate').html(birthDate.toLocaleDateString('pt-BR'))
            $('#patientCode').html(data.patient.code)
            $('#entryDate').val(data.entry)
            $('#exitDate').val(data.exit)
            $('#guide').val(data.guide)
            $('#patientImg').attr('src', data.patient.image)
            $('#preLoader').attr('style', 'display: none !important');
            $('#patientCard').show()
        },
        error: function (error) {
            $('#errorMessage').show();
            $('#preLoader').attr('style', 'display: none !important');
        }
    });
}
