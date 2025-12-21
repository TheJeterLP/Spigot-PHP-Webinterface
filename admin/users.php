<?php
require_once __DIR__ . '/../includes/head.php';
require_once __DIR__ . '/../database/db.php';
requireRole('admin');

$users = $db->query(
    "SELECT id, username, role, active, created_at FROM users ORDER BY id"
)->fetchAll();
?>
<h1>Users</h1>

<!-- Benutzer anlegen -->
<form class="row g-2 mb-4" method="post" action="/functions/admin/user_actions.php">
    <input type="hidden" name="action" value="create">
    <div class="col">
        <input class="form-control" name="username" placeholder="Username" required>
    </div>
    <div class="col">
        <input class="form-control" name="password" placeholder="Password" required>
    </div>
    <div class="col">
        <select class="form-select" name="role">
            <option value="viewer">Viewer</option>
            <option value="operator">Operator</option>
            <option value="admin">Admin</option>
        </select>
    </div>
    <div class="col">
        <button class="btn btn-success">Create</button>
    </div>
</form>

<table class="table table-striped align-middle">
<thead>
<tr>
    <th>ID</th>
    <th>User</th>
    <th>Role</th>
    <th>State</th>
    <th>Actions</th>
</tr>
</thead>
<tbody>

<?php foreach ($users as $u): ?>
<tr>
    <td><?= $u['id'] ?></td>
    <td><?= htmlspecialchars($u['username']) ?></td>
    <td><?= $u['role'] ?></td>
    <td><?= $u['active'] ? 'Active' : 'Inactive' ?></td>
    <td class="d-flex gap-1">

        <!-- Rolle ändern -->
        <form method="post" action="/functions/admin/user_actions.php">
            <input type="hidden" name="action" value="role">
            <input type="hidden" name="id" value="<?= $u['id'] ?>">
            <select name="role" onchange="this.form.submit()" class="form-select form-select-sm">
                <option <?= $u['role']=='viewer'?'selected':'' ?>>viewer</option>
                <option <?= $u['role']=='operator'?'selected':'' ?>>operator</option>
                <option <?= $u['role']=='admin'?'selected':'' ?>>admin</option>
            </select>
        </form>

        <!-- Aktiv / Sperren -->
        <form method="post" action="/functions/admin/user_actions.php">
            <input type="hidden" name="action" value="toggle">
            <input type="hidden" name="id" value="<?= $u['id'] ?>">
            <button class="btn btn-sm btn-warning">Toggle</button>
        </form>

        <!-- Löschen -->
        <form method="post" action="/functions/admin/user_actions.php"
              onsubmit="return confirm('Really delete this user?')">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?= $u['id'] ?>">
            <button class="btn btn-sm btn-danger">X</button>
        </form>

    </td>
</tr>
<?php endforeach; ?>

</tbody>
</table>

<?php
require_once __DIR__ . '/../includes/foot.php';
?>
