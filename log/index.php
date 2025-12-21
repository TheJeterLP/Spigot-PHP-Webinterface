<?php
require_once __DIR__ . '/../includes/head.php';
requireRole('admin');
?>
<div class="bg-body-tertiary p-5 rounded">
    <?php
    if (isset($output) && trim($output) != "") {
    ?>
        <h3>Letzte Aktionen:</h3>
        <pre id="output"><?= htmlspecialchars($output) ?></pre>
    <?php
    }
    ?>


    <h3>Server Log:</h3>
    <pre id="server-log"></pre>
</div>

<script>
    function updateLog() {
        fetch('/functions/log.php')
            .then(response => response.text())
            .then(html => {
                const logEl = document.getElementById('server-log');
                logEl.innerHTML = html;
            }).catch(err => appendAlert(err, 'danger'));
    }

    setInterval(updateLog, 500);
    updateLog();
</script>

<?php
require_once __DIR__ . '/../includes/foot.php';
?>