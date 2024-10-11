$(document).ready(function () {
    let refreshInterval = 15000;
    let table;

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
                {
                    "data": "patient", render: function (data, type, row) {
                        return row.patient.name
                    }
                },
                {"data": "guide"},
                {
                    "data": "entry",
                    "render": function (data, type, row) {
                        let entryDate = new Date(data);
                        return entryDate.toLocaleDateString('pt-BR');
                    }
                },
                {
                    "data": "exit",
                    "render": function (data, type, row) {
                        let entryDate = new Date(data);
                        return entryDate.toLocaleDateString('pt-BR');
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
            "paging": true
        });
    }

    initTable();


    function updateTable() {
        table.ajax.reload(null, false);
    }

    setInterval(updateTable, refreshInterval);
});

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
                url: statisticsRoute + '/' + id,
                type: 'DELETE',
                success: function (response) {
                    Swal.fire(
                        'Excluido!',
                        'Registro excluido com sucesso!',
                        'success'
                    ).then(() => {
                        $('#dataTable').DataTable().ajax.reload(null, false);
                    });
                }
            })
        }
    });
}

function toggleRegister() {
    Swal.fire({
        title: 'Registrar Paciente',
        html:
            `<div class="form-group">
                <label for="name">Nome</label>
                <input type="text" id="name" class="form-control">
            </div>

            <div class="form-group">
                <label for="birth">Data de Nascimento</label>
                <input type="date" id="birth" class="form-control">
            </div>

            <div class="form-group">
                <label for="code">Código</label>
                <div class="input-group">
                    <input type="text" id="code" class="form-control">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button" id="generateCode">Gerar</button>
                    </div>
                </div>
            </div>`,
        showCancelButton: true,
        confirmButtonText: 'Registrar',
        preConfirm: () => {
            let name = $('#name').val();
            let birth = $('#birth').val();
            let code = $('#code').val();

            if (!name || !birth || !code) {
                Swal.showValidationMessage('Preencha todos os campos!')
                return false;
            }

            return {
                name: name,
                birth: birth,
                code: code
            };
        }
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: statisticsRoute,
                type: 'POST',
                data: result.value,
                success: function (response) {
                    Swal.fire(
                        'Registrado!',
                        'Paciente registrado com sucesso!',
                        'success'
                    ).then(() => {
                        $('#dataTable').DataTable().ajax.reload(null, false);
                    });
                }
            })
        }
    });

    $('#generateCode').click(function () {
        let code = Math.floor(100000 + Math.random() * 900000);
        $('#code').val(code);
    });
}
