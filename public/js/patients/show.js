let currentNav = 'patient';
let patientName = null;
$(document).ready(function () {
    $.ajax({
        url: statisticsRoute,
        type: 'GET',
        success: function (data) {
            console.log(data);
            patientName = data.name
            let birthDate = new Date(data.birth);
            $('#patientName').html(patientName)
            $('#birthDate').html(birthDate.toLocaleDateString('pt-BR'))
            $('#patientCode').html(data.code)
            $('#preLoader').attr('style', 'display: none !important');
            $('#patientCard').show()
            $('#patientImg').attr('src', data.image)
        },
        error: function (error) {
            $('#errorMessage').show();
            $('#preLoader').attr('style', 'display: none !important');
        }
    });
    $('#navInternments').on('click', function () {
        navInternments()
    });
    $('#navData').on('click', function () {
        navData();
    });
    $('#dataTable').DataTable({
        searching: false,
        ordering: false,
        "language": {
            "sEmptyTable": "Nenhum registro encontrado",
            "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
            "sInfoFiltered": " - filtrados de _MAX_ registros",
            "sInfoPostFix": "",
            "sInfoThousands": ".",
            "sLengthMenu": "Mostrar _MENU_ registros por página",
            "sLoadingRecords": "Carregando...",
            "sProcessing": "Processando...",
            "sSearch": "Pesquisar",
            "sZeroRecords": "Nenhum registro encontrado",
            "oPaginate": {
                "sFirst": "Primeiro",
                "sLast": "Último",
                "sNext": "Próximo",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Ordenar colunas de forma ascendente",
                "sSortDescending": ": Ordenar colunas de forma descendente"
            }
        },
        "processing": true,
        "serverSide": true,
        "ajax": function (data, callback, settings) {
            var page = (data.start / data.length) + 1;
            var perPage = data.length;

            $.ajax({
                url: internmentsRoute,
                type: 'GET',
                data: {
                    page: page,
                    perPage: perPage
                },
                success: function (response) {
                    $('#dataTable').show();
                    callback({
                        draw: data.draw,
                        recordsTotal: response.total,
                        recordsFiltered: response.total,
                        data: response.data
                    });
                }
            });
        },
        "columns": [
            {"data": null, "render": function () {
                return patientName;
            }},
            {"data": "guide"},
            {
                "data": "entry",
                "render": function (data, type, row) {
                    let entryDate = new Date(data + 'T00:00:00');
                    return entryDate.toLocaleDateString('pt-BR');
                }
            },{
                "data": "exit",
                "render": function (data, type, row) {
                    let entryDate = new Date(data + 'T00:00:00');
                    return entryDate.toLocaleDateString('pt-BR');
                }
            }
        ],
        "pageLength": 10,
        "lengthMenu": [5, 10, 25, 50],
        "paging": true
    });
})

function navInternments() {
    currentNav = 'internments';
    $('#navData').removeClass('active');
    $('#navInternments').addClass('active')
    $('#internmentsTable').show();
    $('#patientData').hide();
}

function navData()
{
    currentNav = 'data';
    $('#navInternments').removeClass('active')
    $('#navData').addClass('active');
    $('#internmentsTable').hide();
    $('#patientData').show();
}
