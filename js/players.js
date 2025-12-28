function updatePlayers() {
    fetch("#", {method: 'POST'})
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
                let rows = "";
                data.players.forEach(p => {
                    const name = mcColorToHtml(p.name);
                    const head = `/functions/skins.php?mode=heads&uuid=${p.uuid}`;
                    const bodyImg = `/functions/skins.php?mode=skins&uuid=${p.uuid}`;
                    const cape = `/functions/skins.php?mode=capes&uuid=${p.uuid}`;

                    rows += `
                <tr>
                    <td>
                        <div class="head-wrapper">
                            <img src="${head}" class="player-head" onerror="this.remove()">
                            <div class="skin-preview">
                                <img src="${bodyImg}" class="skin-img" onerror="this.remove()">
                                <img src="${cape}" class="cape-img" onerror="this.remove()">
                            </div>
                        </div>
                    </td>
                    <td>${name}</td>
                </tr>`;
                });
                body.innerHTML = rows;
            }).catch(err => appendAlert(err, 'danger'));
}

setInterval(updatePlayers, 5000);
updatePlayers();