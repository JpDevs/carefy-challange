let internmentsCount = null;
let censusCount = null;
$(document).ready(function () {
    updateStatistics()
    setInterval(updateStatistics, 10000);
});



function updateStatistics() {
    let table = $('#dataTable tbody');
    let dataTable = $('#dataTable')
    $.ajax({
        url: statisticsRoute,
        method: "GET",
        xhrFields: {
            withCredentials: true
        },
        success: function (data) {
            setTable(table, dataTable, data)
            setInternmentsCount(data.internments.count)
            setPendingInternmentsCount(data.drafts)
            setDoneInternmentsCount(data.internments.doneCount)
            setPatientsCount(data.patients)
        }
    });
}

function setTable(table, dataTable, data) {
    table.empty();
    let internments = data.internments.recent.data
    $.each(internments, function (index, internment) {

        let entryDate = new Date(internment.entry);
        let exitDate = new Date(internment.exit);
        let entryFormatted = entryDate.toLocaleDateString('pt-BR');
        let exitFormatted = exitDate.toLocaleDateString('pt-BR');

        table.append("<tr><td>" + internment.patient.name + "</td>" +
            "<td>" + internment.guide + "</td>" +
            "<td>" + entryFormatted + "</td>" +
            "<td>" + exitFormatted + "</td>" +
            '<td><button class="btn btn-primary"><i class="fas fa-eye"></i></button>\n' +
            '                        <button class="btn btn-danger"><i class="fas fa-trash"></i></button>')
    });
    if (!dataTable.DataTable) {
        dataTable.DataTable({
            "searching": false,
            "paging": false,
            "info": false,
            "language": {
                "sEmptyTable": "Nenhum registro encontrado",
            }
        });
    }
    $('#tablePreLoader').attr('style', 'display: none !important');
    dataTable.show()
}

function setInternmentsCount(count) {
    let card = $('#internments')
    card.text(count)
    if (internmentsCount !== null && count > internmentsCount) {
        let audio = new Audio(internmentAudio);
        audio.play();
    }
    internmentsCount = count;
    return count;
}

function setPendingInternmentsCount(count) {
    let card = $('#pendingInternments')
    card.text(count)
    if (censusCount !== null && count > censusCount) {
        let audio = new Audio(censusAudio);
        audio.play();
    }
    censusCount = count;
    return count;
}

function setDoneInternmentsCount(count) {
    let card = $('#doneInternments')
    card.text(count)
    return count;
}

function setPatientsCount(count) {
    let card = $('#patients')
    card.text(count)
    return count;
}
