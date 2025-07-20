<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<a href="/rollercoasters" class="btn btn-link mb-3">&larr; Powrót do listy kolejek</a>
<h2 class="mb-3">Szczegóły kolejki górskiej</h2>
<div id="coasterDetails" class="mb-4"><!-- Szczegóły będą ładowane przez JS --></div>
<div id="alerts"></div>
<div id="statusAlerts"></div>
<div class="mb-3 text-end">
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#wagonModal">Dodaj wagon</button>
</div>
<table class="table table-striped" id="wagonsTable">
    <thead>
        <tr>
            <th>#</th>
            <th>Ilość miejsc</th>
            <th>Prędkość (m/s)</th>
            <th>Akcje</th>
        </tr>
    </thead>
    <tbody>
        <!-- Wiersze będą generowane przez JS -->
    </tbody>
</table>
<!-- Modal dodawania wagonu -->
<div class="modal fade" id="wagonModal" tabindex="-1" aria-labelledby="wagonModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="wagonModalLabel">Dodaj wagon</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zamknij"></button>
      </div>
      <div class="modal-body">
        <form id="wagonForm">
          <input type="hidden" id="wagonId">
          <div class="mb-3">
            <label for="ilosc_miejsc" class="form-label">Ilość miejsc</label>
            <input type="number" class="form-control" id="ilosc_miejsc" required min="1">
          </div>
          <div class="mb-3">
            <label for="predkosc_wagonu" class="form-label">Prędkość wagonu (m/s)</label>
            <input type="number" step="0.01" class="form-control" id="predkosc_wagonu" required min="0.01">
          </div>
          <button type="submit" class="btn btn-primary">Zapisz</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script src="/js/wagons.js"></script>
<?= $this->endSection() ?> 