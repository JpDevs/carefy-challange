let birthDate = null;
$(document).ready(function () {
    getPatients();
    $('#patients').on('change', function () {
        let patientId = $(this).val();
        if (patientId === '') {
            $('#patientData').hide()
            $('#patientImg').attr('src', noImg)
            return;
        }
        $.ajax({
            url: patientsRoute + '/' + patientId,
            type: 'GET',
            success: function (data) {
                birth = data.birth
                birthDate = new Date(birth + 'T00:00:00');
                $('#patientData').show()
                $('#patientImg').attr('src', data.image)
                $('#birthDate').html(birthDate.toLocaleDateString('pt-BR'))
                $('#patientCode').html(data.code)
            }
        })
    })
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
        if ($('#patients').val() === '' || $('#entryDate').val() === '' || $('#exitDate').val() === '') {
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
        })
        let formData = new FormData($("form[name='internmentForm']")[0])
        formData.append('patient_id', $('#patients').val())
        $.ajax({
            url: internmentsRoute,
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                Swal.fire({
                    title: 'Sucesso!',
                    text: 'Internação cadastrada com sucesso.',
                    icon: 'success',
                    confirmButtonText: 'Ok'
                }).then(function () {
                    window.location.href = redirectRoute
                })
            },
            error: function (error) {
                Swal.fire(
                    'Erro!',
                    'Erro ao criar internação. Verifique se não há internações cadastradas neste periodo e tente novamente.',
                    'error'
                )
            }
        })
    });
});

function getPatients() {
    let patientsSelect = $('#patients')
    $.ajax({
        url: patientsRoute,
        type: 'GET',
        data: {
            noPaginate: true
        },
        success: function (data) {
            $('#preLoader').attr('style', 'display: none !important');
            $('#patientCard').show();
            data.forEach(patient => {
                patientsSelect.append(`<option value="${patient.id}">${patient.name}</option>`)
            });
        }
    })
}
