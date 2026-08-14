<?php
?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <?php if (!empty($jsAuth)): ?>
    <script src="js/<?= htmlspecialchars($jsAuth) ?>"></script>
    <?php endif; ?>

</body>

</html>
