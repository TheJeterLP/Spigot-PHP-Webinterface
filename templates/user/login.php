<form method="post">
    <img class="mb-4" src="/img/logo.png" alt="" width="75" height="75">
    <h1 class="h3 mb-3 fw-normal">Please sign in</h1>

    <?php if (isset($data['error'])) {
        ?>
        <div class="alert alert-danger" role="alert">
            <?= $data['error'] ?>
        </div>
        <?php
    }
    ?>


    <div class="form-floating">
        <input type="text" name="username" class="form-control" id="floatingInput" placeholder="Username">
        <label for="floatingInput">Username</label>
    </div>
    <div class="form-floating">
        <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password">
        <label for="floatingPassword">Password</label>
    </div>
    <button class="btn btn-primary w-100 py-2" type="submit">Sign in</button>
    <p class="mt-5 mb-3 text-body-secondary">&copy; 2025 Joey Peter</p>
</form>
