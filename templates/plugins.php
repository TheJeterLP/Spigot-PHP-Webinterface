<div class="bg-body-tertiary p-5 rounded">
    <table class="plugin-table">
        <thead>
            <tr>
                <th>Plugin</th>
                <th>Version</th>
                <th>State</th>
            </tr>
        </thead>
        <tbody id="plugin-table-body">
            <?php
            $plugins = $data;
            if (empty($plugins)) {
                echo '<tr><td colspan="3">No Plugins installed!</td></tr>';
            } else {
                foreach ($plugins as $pl) {
                    if ($pl['enabled'] === true) {
                        $statusBadge = '<span class="badge text-bg-success">Active</span>';
                    } else if ($pl['enabled'] === false) {
                        $statusBadge = '<span class="badge text-bg-danger">Inactive</span>';
                    } else {
                        $statusBadge = '<span class="badge text-bg-secondary">Unknown</span>';
                    }
                    echo '<tr>';
                    echo '<td>' . $pl['name'] . '</td>';
                    echo '<td>' . $pl['version'] . '</td>';
                    echo '<td>' . $statusBadge . '</td>';
                    echo '</tr>';
                }
            }
            ?>
        </tbody>
    </table>
</div>