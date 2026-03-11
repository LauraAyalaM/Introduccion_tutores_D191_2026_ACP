<?php include __DIR__ . '/../../includes/header.php'; ?>
<?php include __DIR__ . '/../../includes/navbar.php'; ?>

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Videos recomendados</h2>
            <small class="text-muted">Material sugerido según tus respuestas</small>
        </div>

        <a href="../views/reservas/mis_reservas.php"
           class="btn btn-secondary">
            ← Volver a tutorías
        </a>
    </div>

    <div class="row">

        <?php if(!empty($videos)): ?>

            <?php foreach($videos as $v): ?>

            <div class="col-md-6 col-lg-4 mb-4">

                <div class="card h-100 shadow-sm border-0 rounded-4">

                    <div class="card-body">

                        <h5 class="card-title fw-bold">
                            <?php echo htmlspecialchars($v['titulo']); ?>
                        </h5>

                        <p class="card-text text-muted">
                            <?php echo htmlspecialchars($v['descripcion']); ?>
                        </p>

                    </div>

                    <div class="ratio ratio-16x9">
                        <iframe
                            src="<?php echo htmlspecialchars($v['url']); ?>"
                            title="<?php echo htmlspecialchars($v['titulo']); ?>"
                            allowfullscreen>
                        </iframe>
                    </div>

                </div>

            </div>

            <?php endforeach; ?>

        <?php else: ?>

        <div class="col-12">
            <div class="alert alert-info text-center">
                No se encontraron videos recomendados.
            </div>
        </div>

        <?php endif; ?>

    </div>

</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>