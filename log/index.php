<?php
require_once __DIR__ . '/../includes/head.php';
requireAnyRole(['admin', 'operator']);
?>
<div class="bg-body-tertiary p-5 rounded">
    <?php if (hasUserRole('admin')) { ?>
        <form id="rcon-form" class="d-flex gap-2 mt-3">
            <input class="form-control" id="rcon-command" placeholder="Minecraft Command (ex. say Hello)" autocomplete="off">
            <button class="btn btn-primary">Send</button>
        </form>
        <pre id="rcon-output" class="mt-2"></pre>
    <?php } ?>


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

    setInterval(updateLog, 1500);
    updateLog();

    document.getElementById('rcon-form')?.addEventListener('submit', e => {
                e.preventDefault();

                const cmd = document.getElementById('rcon-command').value;
                const out = document.getElementById('rcon-output');

                fetch('/functions/admin/sendCommand.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'command=' + encodeURIComponent(cmd)
                    })
                    .then(r => r.text())
                    .then(t => {
                        out.textContent = t;
                        document.getElementById('rcon-command').value = '';
                    })
                    .catch(err => appendAlert(err, 'danger'));
            });
</script>

<?php
require_once __DIR__ . '/../includes/foot.php';
?>