$(document).ready(function () {
    initIncongruentsTable();
    initValidsTable();
    $('#navIncongruents').on('click', function () {
        navIncongruents()
    });
    $('#navValids').on('click', function () {
        navValids()
    });
});


function initIncongruentsTable() {
    table = $('#incongruentsTable').DataTable({
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
                    perPage: perPage,
                    onlyValids: 0
                },
                success: function (response) {
                    $('#incongruentsCount').html(response.total)
                    $('#tablePreLoader').attr('style', 'display: none !important');
                    $('#incongruentsCount').show();
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
                "data": "name", "render": function (data, type, row) {
                    if(row.patient_data !== null) {
                        let patientData = JSON.parse(row.patient_data);
                        return '<span style="color: #FFC107"><i class="fas fa-exclamation-triangle"></i> ' + patientData.name + '</span>';
                    }
                    return row.patient ? row.patient.name : 'Undefined';
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
                    let entryDate = new Date(data + 'T00:00:00');
                    return entryDate.toLocaleDateString('pt-BR');
                }
            },
            {
                "data": null,
                "render": function (data, type, row) {
                    let inconsistencies = JSON.parse(data.inconsistencies)
                    return Object.keys(inconsistencies).length.toString();
                }
            },
            {
                "data": null,
                "render": function (data, type, row) {
                    return `
                            <a class="btn btn-info" href="/census/` + row.id + `/edit"><i class="fas fa-edit"></i></a>
                            <button class="btn btn-danger" onclick="deleteDraft(${row.id})"><i class="fas fa-trash"></i></button>
                        `;
                }
            }
        ],
        "pageLength": 10,
        "lengthMenu": [5, 10, 25, 50],
        "paging": true
    });
}
function initValidsTable() {
    table = $('#validsTable').DataTable({
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
                    perPage: perPage,
                    onlyValids: 1
                },
                success: function (response) {
                    $('#validsCount').html(response.total)
                    $('#tablePreLoader').attr('style', 'display: none !important');
                    $('#validsCount').show();
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
                "data": "name", "render": function (data, type, row) {
                    return row.patient ? row.patient.name : 'Undefined';
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
                    let entryDate = new Date(data + 'T00:00:00');
                    return entryDate.toLocaleDateString('pt-BR');
                }
            },
            {
                "data": null,
                "render": function (data, type, row) {
                    return `
                            <button class="btn btn-warning" onclick="saveDraft(${row.id})"><i class="fas fa-save"></i></button>
                            <a class="btn btn-info" href="/census/` + row.id + `/edit"><i class="fas fa-edit"></i></a>
                            <button class="btn btn-danger" onclick="deleteDraft(${row.id})"><i class="fas fa-trash"></i></button>
                        `;
                }
            }
        ],
        "pageLength": 10,
        "lengthMenu": [5, 10, 25, 50],
        "paging": true
    });
}


function navIncongruents() {
    $('#navValids').removeClass('active');
    $('#navIncongruents').addClass('active')
    $('#validsDiv').hide();
    $('#incongruentsDiv').show();
}

function navValids() {
    $('#navIncongruents').removeClass('active')
    $('#navValids').addClass('active');
    $('#validsDiv').show();
    $('#incongruentsDiv').hide();
}

function deleteDraft(id) {
    Swal.fire({
        title: 'Você tem certeza?',
        text: "Essa ação não poderá ser desfeita!",
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
                        $('#incongruentsTable').DataTable().ajax.reload(null, false);
                        $('#validsTable').DataTable().ajax.reload(null, false);
                    });
                }
            })
        }
    });
}

function saveValids() {
    Swal.fire({
        title: 'Confirmação',
        text: "Deseja inserir TODAS as internações válidas no sistema?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, enviar!'
    }).then((result) => {
        if (result.value) {
            Swal.fire({
                title: 'Salvando internações...',
                html: '<div class="spinner-border text-primary mb-2" role="status"><span class="sr-only">Loading...</span></div>',
                showConfirmButton: false,
                allowOutsideClick: false,
                onOpen: () => {
                    Swal.showLoading()
                }
            });
            $.ajax({
                url: statisticsRoute + '/publish',
                type: 'POST',
                success: function (response) {
                    Swal.hideLoading()
                    Swal.fire(
                        'Enviado!',
                        'Internações salvas com sucesso!',
                        'success'
                    ).then(() => {
                        $('#incongruentsTable').DataTable().ajax.reload(null, false);
                        $('#validsTable').DataTable().ajax.reload(null, false);
                    });
                }
            })
        }
    });
}
function toggleImport() {
    Swal.fire({
        title: 'Importar CSV',
        html: `
            <input type="file" id="file" name="file" class="form-control" accept=".csv">
        `,
        showCancelButton: true,
        confirmButtonText: 'Importar',
        preConfirm: () => {
            const file = document.getElementById('file').files[0]
            if(!file){
                Swal.showValidationMessage(
                    `Selecione um arquivo`
                )
                return false
            }
            console.log(file);
            if(file.name.split('.').pop() !== 'csv') {
                Swal.showValidationMessage(
                    `Arquivo inválido!`
                )
                return false
            }
        }
    }).then((result) => {
        if(result.value) {
            let formData = new FormData();
            formData.append('file', document.getElementById('file').files[0]);
            Swal.fire({
                title: 'Importando Internações...',
                html: '<div class="spinner-border text-primary mb-2" role="status"><span class="sr-only">Loading...</span></div>',
                showConfirmButton: false,
                allowOutsideClick: false,
                onOpen: () => {
                    Swal.showLoading()
                }
            });
            $.ajax({
                url: uploadRoute,
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    Swal.hideLoading()
                    Swal.fire(
                        'Importado!',
                        'Internações importadas com sucesso!',
                        'success'
                    ).then(() => {
                        $('#incongruentsTable').DataTable().ajax.reload(null, false);
                        $('#validsTable').DataTable().ajax.reload(null, false);
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

function truncate() {
    Swal.fire({
        title: 'Confirmação',
        text: "Deseja excluir todos os registros?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, excluir!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: truncateRoute,
                type: 'DELETE',
                success: function (response) {
                    Swal.fire(
                        'Excluido!',
                        'Registros excluidos com sucesso!',
                        'success'
                    ).then(() => {
                        $('#incongruentsTable').DataTable().ajax.reload(null, false);
                        $('#validsTable').DataTable().ajax.reload(null, false);
                    });
                }
            })
        }
    });
}

function saveDraft(id) {
    Swal.fire({
        title: 'Confirmação',
        text: "Deseja confirmar a internação?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, confirmar!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: statisticsRoute + '/' + id + '/publish',
                type: 'POST',
                success: function (response) {
                    $('#validsTable').DataTable().ajax.reload(null, false);
                    Swal.fire({
                        title: 'Salvo!',
                        text: 'Internação confirmada com sucesso!',
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Visualizar internaçao',
                        cancelButtonText: 'Fechar'
                    }).then((result) => {
                        if (result.value) {
                            window.location.href = internments + '/' + response.id;
                        }
                    });
                }
            })
        }
    });
}
