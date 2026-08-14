<?php
?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($pageTitle ?? 'Sistema de Administración de Cabinas') ?></title>

    <!-- Bootstrap -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- Bootstrap Icons -->

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- CSS -->

    <link rel="stylesheet"
          href="css/<?= htmlspecialchars($cssAuth ?? 'index.css') ?>">

</head>

<body>

    <?php $conFilaBootstrap = $authRowWrapper ?? false; ?>

    <header class="encabezado">

        <div class="container">

            <?php if ($conFilaBootstrap): ?><div class="row align-items-center"><div class="col"><?php endif; ?>

                    <h1>
                        <i class="bi bi-house-door-fill"></i>
                        <?= $authTituloHtml ?? 'Sistema de Administración de Cabinas' ?>
                    </h1>

                    <p><?= htmlspecialchars($authSubtitulo ?? '') ?></p>

            <?php if ($conFilaBootstrap): ?></div></div><?php endif; ?>

        </div>

    </header>
