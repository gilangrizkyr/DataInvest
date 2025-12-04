<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid mt-4">

    <h4 class="fw-bold mb-3">📊 Peringkat Jumlah Proyek per Kecamatan (PMDN & PMA)</h4>

    <div class="row">

        <!-- ======================== CARD PMDN ======================== -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">

                    <h5 class="fw-bold">Peringkat Proyek PMDN</h5>
                    <p class="text-muted mb-3">Urutan kecamatan berdasarkan jumlah proyek PMDN.</p>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-primary">
                                <tr>
                                    <th width="40">#</th>
                                    <th>Kecamatan</th>
                                    <th class="text-center">Jumlah Proyek</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no=1; foreach($peringkatPMDN as $p): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $p->kecamatan ?></td>
                                    <td class="text-center fw-bold"><?= $p->total_proyek ?></td>
                                </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>

                    <canvas id="chartPMDN" height="140"></canvas>
                </div>
            </div>
        </div>

        <!-- ======================== CARD PMA ======================== -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">

                    <h5 class="fw-bold">Peringkat Proyek PMA</h5>
                    <p class="text-muted mb-3">Urutan kecamatan berdasarkan jumlah proyek PMA.</p>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-success">
                                <tr>
                                    <th width="40">#</th>
                                    <th>Kecamatan</th>
                                    <th class="text-center">Jumlah Proyek</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no=1; foreach($peringkatPMA as $p): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $p->kecamatan ?></td>
                                    <td class="text-center fw-bold"><?= $p->total_proyek ?></td>
                                </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>

                    <canvas id="chartPMA" height="140"></canvas>
                </div>
            </div>
        </div>

    </div>

</div>


<!-- ======================== CHART JS ======================== -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // ---------------------- CHART PMDN ----------------------
    const ctxPMDN = document.getElementById('chartPMDN');
    new Chart(ctxPMDN, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($peringkatPMDN, 'kecamatan')) ?>,
            datasets: [{
                label: 'Jumlah Proyek PMDN',
                data: <?= json_encode(array_column($peringkatPMDN, 'total_proyek')) ?>,
            }]
        }
    });

    // ---------------------- CHART PMA ----------------------
    const ctxPMA = document.getElementById('chartPMA');
    new Chart(ctxPMA, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($peringkatPMA, 'kecamatan')) ?>,
            datasets: [{
                label: 'Jumlah Proyek PMA',
                data: <?= json_encode(array_column($peringkatPMA, 'total_proyek')) ?>,
            }]
        }
    });
</script>

<?= $this->endSection() ?>
