let counters = {
    internments: null,
    patients: null,
    drafts: null,
    done: null
};
let table = null;
$('document').ready(function () {
    function initTable() {
        table = $('#dataTable').DataTable({
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
                    url: statisticsRoute,
                    type: 'GET',
                    data: {
                        page: page,
                        perPage: perPage
                    },
                    success: function (response) {
                        $('#tablePreLoader').attr('style', 'display: none !important');
                        $('#dataTable').show();

                        if (counters.drafts !== null && response.drafts > counters.drafts) {
                            new Audio(censusAudio).play();
                        }

                        if(counters.internments !== null && response.internments.count > counters.internments) {
                            new Audio(internmentAudio).play();
                        }


                        counters.internments = response.internments.count;
                        counters.patients = response.patients;
                        counters.drafts = response.drafts;
                        counters.done = response.internments.doneCount;

                        $('#internments').html(counters.internments);
                        $('#patients').html(counters.patients);
                        $('#pendingInternments').html(counters.drafts);
                        $('#doneInternments').html(counters.done);

                        callback({
                            draw: data.draw,
                            recordsTotal: response.internments.count,
                            recordsFiltered: response.internments.count,
                            data: response.internments.recent.data
                        });
                    }
                });
            },
            "columns": [
                {
                    "data": "patient", render: function (data, type, row) {
                        return row.patient.name;
                    }
                },
                {"data": "guide"},
                {
                    "data": "entry",
                    "render": function (data, type, row) {
                        let entryDate = new Date(data + 'T00:00:00');
                        return entryDate.toLocaleDateString('pt-BR');
                    }
                },
                {
                    "data": "exit",
                    "render": function (data, type, row) {
                        if (data === null) {
                            return 'Não Programada';
                        }
                        let exitDate = new Date(data + 'T00:00:00');
                        return exitDate.toLocaleDateString('pt-BR');
                    }
                },
                {
                    "data": null,
                    "render": function (data, type, row) {
                        return `
                        <a class="btn btn-primary" href="/internments/` + row.id + `"><i class="fas fa-eye"></i></a>
                        <a class="btn btn-info" href="/internments/` + row.id + `/edit"><i class="fas fa-edit"></i></a>
                        <button class="btn btn-danger" onclick="deleteInternment(${row.id})"><i class="fas fa-trash"></i></button>
                    `;
                    }
                }
            ],
            "pageLength": 10,
            "lengthMenu": [5, 10, 25, 50],
            "paging": false
        });
    }
    initTable();

    setInterval(updateStatistics, 15000);

});


function updateStatistics() {
    table.ajax.reload(null, false);
}

function deleteInternment(id) {
    Swal.fire({
        title: 'Você tem certeza?',
        text: "Você poderá recuperar a internação na lixeira.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, deletar!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: internmentsApi + '/' + id,
                type: 'DELETE',
                success: function (response) {
                    Swal.fire(
                        'Excluido!',
                        'Registro excluido com sucesso!',
                        'success'
                    ).then(() => {
                        // counters.internments.count = counters.internments.count - 1;
                        updateStatistics();
                    });
                }
            })
        }
    });
}
