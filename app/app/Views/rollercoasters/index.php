<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<h1 class="mb-4">Lista kolejek górskich</h1>
<div id="alerts"></div>
<div class="mb-3 d-flex justify-content-between">
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#coasterModal">Dodaj kolejkę</button>
    <a href="/rollercoasters/personnel" class="btn btn-outline-primary">Panel personelu</a>
</div>
<div id="statusAlerts"></div>
<table class="table table-striped" id="coastersTable">
    <thead>
        <tr>
            <th>#</th>
            <th>Liczba personelu</th>
            <th>Liczba klientów</th>
            <th>Długość trasy (m)</th>
            <th>Godziny</th>
            <th>Akcje</th>
        </tr>
    </thead>
    <tbody>
        <!-- Wiersze będą generowane przez JS -->
    </tbody>
</table>
<!-- Modal dodawania/edycji kolejki -->
<div class="modal fade" id="coasterModal" tabindex="-1" aria-labelledby="coasterModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="coasterModalLabel">Dodaj kolejkę górską</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zamknij"></button>
      </div>
      <div class="modal-body">
        <form id="coasterForm">
          <input type="hidden" id="coasterId">
          <div class="mb-3">
            <label for="liczba_personelu" class="form-label">Liczba personelu</label>
            <input type="number" class="form-control" id="liczba_personelu" required min="1">
          </div>
          <div class="mb-3">
            <label for="liczba_klientow" class="form-label">Liczba klientów dziennie</label>
            <input type="number" class="form-control" id="liczba_klientow" required min="1">
          </div>
          <div class="mb-3">
            <label for="dl_trasy" class="form-label">Długość trasy (m)</label>
            <input type="number" class="form-control" id="dl_trasy" required min="1">
          </div>
          <div class="mb-3 row">
            <div class="col">
              <label for="godziny_od" class="form-label">Godzina od</label>
              <input type="time" class="form-control" id="godziny_od" required>
            </div>
            <div class="col">
              <label for="godziny_do" class="form-label">Godzina do</label>
              <input type="time" class="form-control" id="godziny_do" required>
            </div>
          </div>
          <button type="submit" class="btn btn-primary">Zapisz</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script src="/js/rollercoasters.js"></script>
<?= $this->endSection() ?> 