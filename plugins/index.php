<?php
require_once __DIR__ . '/../includes/head.php';
requireAnyRole(['admin', 'operator']);
?>

<style>
    .plugin-table {
        width: 100%;
        border-collapse: collapse;
    }

    .plugin-table th,
    .plugin-table td {
        padding: 6px 8px;
        text-align: left;
    }

    .plugin-table tr:not(:last-child) {
        border-bottom: 1px solid #333;
    }
</style>

<div class="bg-body-tertiary p-5 rounded">
    <table class="plugin-table">
        <thead>
            <tr>
                <th>Plugin</th>
                <th>State</th>
            </tr>
        </thead>
        <tbody id="plugin-table-body">
            <tr>
                <td colspan="2">Loading...</td>
            </tr>
        </tbody>
    </table>
</div>

<script>
    function updatePlugins() {
        fetch("/functions/plugins.php")
            .then(res => res.json())
            .then(data => {
                console.log(data);
                const body = document.getElementById("plugin-table-body");

                if (!data.online) {
                    body.innerHTML = `
                    <tr>
                        <td colspan="2" style="color:red">Server offline</td>
                    </tr>`;
                    return;
                }

                if (data.count === 0 || data.plugins.length === 0) {
                    body.innerHTML = `
                    <tr>
                        <td colspan="1">No Plugins installed</td>
                    </tr>`;
                    return;
                }
                body.innerHTML = "";

                data.plugins.forEach(p => {
                const statusBadge = p.enabled === true ? '<span class="badge text-bg-success">Active</span>' : p.enabled === false? '<span class="badge text-bg-danger">Inactive</span>': '<span class="badge text-bg-secondary">Unknown</span>';
                    body.innerHTML += `
                <tr>                  
                    <td>${p.name}</td>
                    <td>${statusBadge}</td>
                </tr>`;
                });

            }).catch(err => appendAlert(err, 'danger'));
    }

    setInterval(updatePlugins, 5000);
    updatePlugins();
</script>

<?php
require_once __DIR__ . '/../includes/foot.php';
?>