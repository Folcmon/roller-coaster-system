<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<a href="/rollercoasters" class="btn btn-link mb-3">&larr; Powrót do listy kolejek</a>
<h2 class="mb-3">Zarządzanie personelem</h2>
<div id="alerts"></div>
<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">Dostępny personel</h5>
        <p class="card-text">
            <span id="personnelCount">...</span> pracowników
        </p>
        <form id="personnelForm" class="row g-3">
            <div class="col-auto">
                <label for="personnelInput" class="visually-hidden">Nowa liczba personelu</label>
                <input type="number" class="form-control" id="personnelInput" min="0" required placeholder="Nowa liczba">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary mb-3">Zmień</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script src="/js/personnel.js"></script>
<?= $this->endSection() ?> 