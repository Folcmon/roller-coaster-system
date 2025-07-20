// Obsługa widoku kolejek górskich
$(document).ready(function () {
    fetchCoasters();
    fetchStatusAlerts();

    $('#coasterForm').on('submit', function (e) {
        e.preventDefault();
        saveCoaster();
    });

    $('#coasterModal').on('hidden.bs.modal', function () {
        clearCoasterForm();
    });
});

function fetchCoasters() {
    $.get('/api/coasters', function (data) {
        renderCoastersTable(data);
    }).fail(function () {
        showAlert('Błąd pobierania kolejek.', 'danger');
    });
}

function renderCoastersTable(coasters) {
    const tbody = $('#coastersTable tbody');
    tbody.empty();
    if (!coasters.length) {
        tbody.append('<tr><td colspan="6" class="text-center">Brak kolejek</td></tr>');
        return;
    }
    coasters.forEach(function (coaster, idx) {
        tbody.append(`
            <tr>
                <td>${idx + 1}</td>
                <td>${coaster.liczbaPersonelu}</td>
                <td>${coaster.liczbaKlientow}</td>
                <td>${coaster.dlugoscTrasy}</td>
                <td>${coaster.godzinyOd} - ${coaster.godzinyDo}</td>
                <td>
                    <button class="btn btn-primary btn-sm me-1" onclick="editCoaster('${coaster.id}')">Edytuj</button>
                    <button class="btn btn-secondary btn-sm me-1" onclick="viewWagons('${coaster.id}')">Wagony</button>
                    <button class="btn btn-danger btn-sm" onclick="deleteCoaster('${coaster.id}')">Usuń</button>
                </td>
            </tr>
        `);
    });
}

function saveCoaster() {
    const id = $('#coasterId').val();
    const data = {
        liczbaPersonelu: $('#liczba_personelu').val(),
        liczbaKlientow: $('#liczba_klientow').val(),
        dlugoscTrasy: $('#dl_trasy').val(),
        godzinyOd: $('#godziny_od').val(),
        godzinyDo: $('#godziny_do').val()
    };
    const method = id ? 'PUT' : 'POST';
    const url = id ? `/api/coasters/${id}` : '/api/coasters';
    $.ajax({
        url: url,
        method: method,
        contentType: 'application/json',
        data: JSON.stringify(data),
        success: function () {
            $('#coasterModal').modal('hide');
            fetchCoasters();
            showAlert('Kolejka zapisana!', 'success');
        },
        error: function () {
            showAlert('Błąd zapisu kolejki.', 'danger');
        }
    });
}

function editCoaster(id) {
    $.get(`/api/coasters/${id}`, function (coaster) {
        $('#coasterId').val(coaster.id);
        $('#liczba_personelu').val(coaster.liczbaPersonelu);
        $('#liczba_klientow').val(coaster.liczbaKlientow);
        $('#dl_trasy').val(coaster.dlugoscTrasy);
        $('#godziny_od').val(coaster.godzinyOd);
        $('#godziny_do').val(coaster.godzinyDo);
        $('#coasterModalLabel').text('Edytuj kolejkę górską');
        $('#coasterModal').modal('show');
    });
}

function clearCoasterForm() {
    $('#coasterForm')[0].reset();
    $('#coasterId').val('');
    $('#coasterModalLabel').text('Dodaj kolejkę górską');
}

function deleteCoaster(id) {
    if (!confirm('Czy na pewno chcesz usunąć tę kolejkę?')) return;
    $.ajax({
        url: `/api/coasters/${id}`,
        method: 'DELETE',
        success: function () {
            fetchCoasters();
            showAlert('Kolejka usunięta.', 'success');
        },
        error: function () {
            showAlert('Błąd usuwania kolejki.', 'danger');
        }
    });
}

function showAlert(message, type) {
    $('#alerts').html(`<div class="alert alert-${type} alert-dismissible fade show" role="alert">
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Zamknij"></button>
    </div>`);
}

function viewWagons(coasterId) {
    // Przekierowanie do widoku wagonów danej kolejki
    window.location.href = `/rollercoasters/${coasterId}/wagons`;
}

function fetchStatusAlerts() {
    $.get('/api/coasters/status', function (data) {
        renderStatusAlerts(data);
    });
}

function renderStatusAlerts(statusList) {
    const container = $('#statusAlerts');
    container.empty();
    if (!statusList.length) return;
    statusList.forEach(function (status) {
        let type = 'info';
        if (status.type === 'brak') type = 'danger';
        if (status.type === 'nadmiar') type = 'warning';
        container.append(`<div class="alert alert-${type} alert-dismissible fade show" role="alert">
            <strong>${status.coaster}:</strong> ${status.message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Zamknij"></button>
        </div>`);
    });
} 