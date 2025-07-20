// Panel zarządzania personelem
document.addEventListener('DOMContentLoaded', function () {
    fetchPersonnel();
    document.getElementById('personnelForm').addEventListener('submit', function (e) {
        e.preventDefault();
        updatePersonnel();
    });
});

function fetchPersonnel() {
    fetch('/api/coasters/personnel')
        .then(res => res.json())
        .then(data => {
            document.getElementById('personnelCount').textContent = data.personnel;
        })
        .catch(() => showAlert('Błąd pobierania liczby personelu.', 'danger'));
}

function updatePersonnel() {
    const value = document.getElementById('personnelInput').value;
    fetch('/api/coasters/personnel', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ personnel: value })
    })
        .then(res => {
            if (!res.ok) throw new Error();
            return res.json();
        })
        .then(() => {
            fetchPersonnel();
            showAlert('Liczba personelu zaktualizowana.', 'success');
        })
        .catch(() => showAlert('Błąd aktualizacji personelu.', 'danger'));
}

function showAlert(message, type) {
    document.getElementById('alerts').innerHTML = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Zamknij"></button>
    </div>`;
} 