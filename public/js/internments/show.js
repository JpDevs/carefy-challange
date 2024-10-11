$(document).ready(function () {
    $.ajax({
        url: statisticsRoute,
        type: 'GET',
        success: function (data) {
            console.log(data);
            patientName = data.patient.name
            let birthDate = new Date(data.patient.birth);
            let entryDate = new Date(data.entry);
            let exitDate = new Date(data.exit);

            $('#patientName').html(patientName)
            $('#birthDate').html(birthDate.toLocaleDateString('pt-BR'))
            $('#entryDate').html(entryDate.toLocaleDateString('pt-BR'))
            $('#exitDate').html(exitDate.toLocaleDateString('pt-BR'))
            $('#guide').html(data.guide)
            $('#patientCode').html(data.patient.code)
            $('#preLoader').attr('style', 'display: none !important');
            $('#patientCard').show()
            $('#patientImg').attr('src', data.patient.image)
        },
        error: function (error) {
            $('#errorMessage').show();
            $('#preLoader').attr('style', 'display: none !important');
        }
    });
})
