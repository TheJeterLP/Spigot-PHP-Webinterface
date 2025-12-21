<?php
require_once __DIR__ . '/../includes/head.php';
requireLogin();
?>

<style>
.player-table {
    width: 100%;
    border-collapse: collapse;
}

.player-table th,
.player-table td {
    padding: 6px 8px;
    text-align: left;
}

.player-table img {
    border-radius: 6px;
    image-rendering: pixelated;
}

.player-table tr:not(:last-child) {
    border-bottom: 1px solid #333;
}

.head-wrapper {
    position: relative;
    width: 64px;
}

.player-head {
    width: 64px;
    height: 64px;
    image-rendering: pixelated;
    border-radius: 6px;
    cursor: pointer;
}

.cape-img {
    width: 64px;
    margin-top: 6px;
    image-rendering: pixelated;
    border-radius: 4px;
}

.skin-preview {
    display: none;
    position: absolute;
    top: 0;
    left: 80px;
    background: #141414;
    padding: 6px;
    border-radius: 6px;
    box-shadow: 0 0 8px rgba(0,0,0,0.6);
    text-align: center;
}

.head-wrapper:hover .skin-preview {
    display: block;
}

.skin-img {
    width: 128px;
    image-rendering: pixelated;
}
</style>

<div class="bg-body-tertiary p-5 rounded">
    <table class="player-table">
        <thead>
            <tr>
                <th>Avatar</th>
                <th>Player</th>
            </tr>
        </thead>
        <tbody id="player-table-body">
            <tr><td colspan="2">Loading...</td></tr>
        </tbody>
    </table>
</div>

<script>
    function updatePlayers() {
        fetch("/functions/players.php")
            .then(res => res.json())
            .then(data => {
                console.log(data);
                const body = document.getElementById("player-table-body");

                if (!data.online) {
                    body.innerHTML = `
                    <tr>
                        <td colspan="2" style="color:red">Server offline</td>
                    </tr>`;
                    return;
                }

                if (data.count === 0) {
                    body.innerHTML = `
                    <tr>
                        <td colspan="2">No Players online</td>
                    </tr>`;
                    return;
                }
                body.innerHTML = "";

                data.players.forEach(p => {
                const head = `/functions/head.php?uuid=${p.uuid}`;
                const bodyImg = `/functions/skin.php?uuid=${p.uuid}`;
                const cape = `/functions/cape.php?uuid=${p.uuid}`;

                body.innerHTML += `
                <tr>
                    <td>
                        <div class="head-wrapper">
                            <img src="${head}" class="player-head" onerror="this.style.display='none'">
                            <div class="skin-preview">
                                <img src="${bodyImg}" class="skin-img" onerror="this.style.display='none'">
                                <img src="${cape}" class="cape-img" onerror="this.style.display='none'">
                            </div>
                        </div>
                    </td>
                    <td>${p.name}</td>
                </tr>`;
            });

            }).catch(err => appendAlert(err, 'danger'));
    }

    setInterval(updatePlayers, 5000);
    updatePlayers();
</script>

<?php
require_once __DIR__ . '/../includes/foot.php';
?>