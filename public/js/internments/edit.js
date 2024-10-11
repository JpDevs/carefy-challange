let patientName = null;
$(document).ready(function () {
    updateData()
    $('#patientImage').change(function () {
        var file = this.files[0];
        var fileType = file["type"];
        var validImageTypes = ["image/gif", "image/jpeg", "image/png"];
        if ($.inArray(fileType, validImageTypes) < 0) {
            Swal.fire(
                'Erro!',
                'Formato inválido',
                'error'
            )
            this.value = '';
            return false;
        }
        var reader = new FileReader();
        reader.onload = function (event) {
            $('#patientImagePreview').attr("src", event.target.result);
        }
        reader.readAsDataURL(file);
    });
    $('#patientForm').on('submit', function (event) {

        event.preventDefault();
        Swal.fire({
            title: 'Aguarde...',
            html: '<div class="spinner-border text-primary mb-2" role="status"><span class="sr-only">Loading...</span></div>',
            showConfirmButton: false,
            allowOutsideClick: false,
            onOpen: () => {
                Swal.showLoading()
            }
        });

        if(!$('#patientName').val() || !$('#birthDate').val() || !$('#patientCode').val()) {
            Swal.fire(
                'Erro!',
                'Preencha todos os campos',
                'error'
            )
            return false;
        }

        let formData = new FormData($("form[name='patientForm']")[0])
        let image = $('#patientImage')[0].files[0];
        formData.append('image', image);
        formData.append('_method', 'PUT')
        $.ajax({
            url: updateRoute,
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                Swal.hideLoading()
                Swal.fire(
                    'Sucesso!',
                    'Paciente atualizado com sucesso',
                    'success'
                ).then(function () {
                    updateData()
                })
            },
            error: function (error) {
                Swal.fire(
                    'Erro!',
                    'Erro ao atualizar paciente',
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
            patientName = data.name
            $('#patientName').val(patientName)
            $('#birthDate').val(data.birth)
            $('#patientCode').val(data.code)
            $('#patientImagePreview').attr('src', data.image)
            $('#preLoader').attr('style', 'display: none !important');
            $('#patientCard').show()
        },
        error: function (error) {
            $('#errorMessage').show();
            $('#preLoader').attr('style', 'display: none !important');
        }
    });
}
