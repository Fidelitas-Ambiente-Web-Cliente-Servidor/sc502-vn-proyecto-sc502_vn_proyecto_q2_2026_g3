<?php
?>
    <footer>
        <p>Sistema de Administración de Cabinas © 2026</p>
        <?php if (!empty($footerModulo)): ?>
        <p><?= htmlspecialchars($footerModulo) ?></p>
        <?php endif; ?>
        <p>Creado por grupo 3</p>
    </footer>

    <?php if (!empty($jsModulo)): ?>
    <script src="js/<?= htmlspecialchars($jsModulo) ?>"></script>
    <?php endif; ?>

</body>

</html>
