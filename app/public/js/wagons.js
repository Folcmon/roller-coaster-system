// Obsługa widoku wagonów danej kolejki
document.addEventListener('DOMContentLoaded', function () {
    const coasterId = getCoasterIdFromUrl();
    fetchCoasterDetails(coasterId);
    fetchWagons(coasterId);
    fetchStatusAlerts(coasterId);

    document.getElementById('wagonForm').addEventListener('submit', function (e) {
        e.preventDefault();
        saveWagon(coasterId);
    });

    document.getElementById('wagonModal').addEventListener('hidden.bs.modal', function () {
        clearWagonForm();
    });
});

function getCoasterIdFromUrl() {
    const parts = window.location.pathname.split('/');
    return parts[2]; // /rollercoasters/{id}/wagons
}

function fetchCoasterDetails(coasterId) {
    fetch(`/api/coasters/${coasterId}`)
        .then(res => res.json())
        .then(coaster => {
            renderCoasterDetails(coaster);
        })
        .catch(() => showAlert('Błąd pobierania szczegółów kolejki.', 'danger'));
}

function renderCoasterDetails(coaster) {
    const html = `
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">ID: ${coaster.id}</h5>
                <p class="card-text">
                    <strong>Liczba personelu:</strong> ${coaster.liczbaPersonelu}<br>
                    <strong>Liczba klientów dziennie:</strong> ${coaster.liczbaKlientow}<br>
                    <strong>Długość trasy:</strong> ${coaster.dlugoscTrasy} m<br>
                    <strong>Godziny działania:</strong> ${coaster.godzinyOd} - ${coaster.godzinyDo}
                </p>
            </div>
        </div>
    `;
    document.getElementById('coasterDetails').innerHTML = html;
}

function fetchWagons(coasterId) {
    fetch(`/api/coasters/${coasterId}/wagons`)
        .then(res => res.json())
        .then(wagons => {
            renderWagonsTable(wagons);
        })
        .catch(() => showAlert('Błąd pobierania wagonów.', 'danger'));
}

function renderWagonsTable(wagons) {
    const tbody = document.querySelector('#wagonsTable tbody');
    tbody.innerHTML = '';
    if (!wagons.length) {
        tbody.innerHTML = '<tr><td colspan="4" class="text-center">Brak wagonów</td></tr>';
        return;
    }
    wagons.forEach(function (wagon, idx) {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${idx + 1}</td>
            <td>${wagon.ilosc_miejsc}</td>
            <td>${wagon.predkosc_wagonu}</td>
            <td>
                <button class="btn btn-danger btn-sm" onclick="deleteWagon('${wagon.id}')">Usuń</button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

function saveWagon(coasterId) {
    const data = {
        ilosc_miejsc: document.getElementById('ilosc_miejsc').value,
        predkosc_wagonu: document.getElementById('predkosc_wagonu').value
    };
    fetch(`/api/coasters/${coasterId}/wagons`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
        .then(res => {
            if (!res.ok) throw new Error();
            return res.json();
        })
        .then(() => {
            bootstrap.Modal.getInstance(document.getElementById('wagonModal')).hide();
            fetchWagons(coasterId);
            showAlert('Wagon dodany!', 'success');
        })
        .catch(() => showAlert('Błąd dodawania wagonu.', 'danger'));
}

function clearWagonForm() {
    document.getElementById('wagonForm').reset();
    document.getElementById('wagonId').value = '';
    document.getElementById('wagonModalLabel').textContent = 'Dodaj wagon';
}

function deleteWagon(wagonId) {
    const coasterId = getCoasterIdFromUrl();
    if (!confirm('Czy na pewno chcesz usunąć ten wagon?')) return;
    fetch(`/api/coasters/${coasterId}/wagons/${wagonId}`, {
        method: 'DELETE'
    })
        .then(res => {
            if (!res.ok) throw new Error();
            fetchWagons(coasterId);
            showAlert('Wagon usunięty.', 'success');
        })
        .catch(() => showAlert('Błąd usuwania wagonu.', 'danger'));
}

function showAlert(message, type) {
    document.getElementById('alerts').innerHTML = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Zamknij"></button>
    </div>`;
}

function fetchStatusAlerts(coasterId) {
    fetch(`/api/coasters/${coasterId}/status`)
        .then(res => res.json())
        .then(statusList => renderStatusAlerts(statusList))
        .catch(() => {});
}

function renderStatusAlerts(statusList) {
    const container = document.getElementById('statusAlerts');
    container.innerHTML = '';
    if (!statusList.length) return;
    statusList.forEach(function (status) {
        let type = 'info';
        if (status.type === 'brak') type = 'danger';
        if (status.type === 'nadmiar') type = 'warning';
        container.innerHTML += `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
            <strong>${status.coaster}:</strong> ${status.message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Zamknij"></button>
        </div>`;
    });
} 