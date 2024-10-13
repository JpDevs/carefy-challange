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
                let birthDate = new Date(data.birth + 'T00:00:00');
                $('#patientData').show()
                $('#patientImg').attr('src', data.image)
                $('#birthDate').html(birthDate.toLocaleDateString('pt-BR'))
                $('#patientCode').html(data.code)
            }
        })
    })
    $('#internmentForm').on('submit', function (event) {
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
                    error.responseJSON.message,
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
