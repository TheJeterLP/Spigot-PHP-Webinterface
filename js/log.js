function updateLog() {
    fetch('#', {method: 'POST'})
            .then(response => response.text())
            .then(html => {
                const logEl = document.getElementById('server-log');
                logEl.innerHTML = html;
            }).catch(err => appendAlert(err, 'danger'));
}

setInterval(updateLog, 1500);

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


