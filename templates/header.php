<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#712cf9">
        <title><?php echo $title; ?></title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
        <?php
        if (isset($css)) {
            echo '<link rel="stylesheet" type="text/css" href ="/css/' . $css . '">';
        }

        if ($headerfooter) {
            ?>
            <link rel="stylesheet" type="text/css" href="/css/style.css">
        <?php } ?>
    </head>

    <?php if (isset($bodyClass)) { ?>
        <body class="<?php echo $bodyClass ?>">
        <?php } else { ?> 
        <body> <?php
        }
        if ($headerfooter) {
            ?>            
            <nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
                <div class="container-fluid">
                    <a class="navbar-brand" href="/">
                        <img src="/img/logo.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top">
                        Minecraft Server Control
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
                    <div class="collapse navbar-collapse" id="navbarCollapse">
                        <?php
                        if (isLoggedIn()) {
                            ?>
                            <ul class="navbar-nav me-auto mb-2 mb-md-0">
                                <li class="nav-item"> <a class="nav-link" aria-current="page" href="/">Status: </a> </li>
                                <li class="nav-item"> <strong id="server-status" class="nav-link">Loading...</strong></li>
                            </ul>

                            <div class="navbar-nav col-lg-6 justify-content-lg-center">
                                <?php if (hasAnyRole(['admin', 'operator'])) { ?>
                                    <form>
                                        <button type="button" class="btn btn-primary" onclick="startServer()">Start</button>
                                        <button type="button" class="btn btn-danger" onclick="stopServer()">Stop</button>
                                        <button type="button" class="btn btn-warning" onclick="reStartServer()">Restart</button>
                                    </form>
                                <?php } ?>
                                <div class="nav-item dropdown">
                                    <button type="button" class="btn btn-outline-primary dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Pages
                                    </button>
                                    <ul class="dropdown-menu">
                                        <?php if (hasAnyRole(['admin', 'operator'])) { ?>
                                            <li><a class="dropdown-item" href="/log">Show Server Log</a></li>
                                            <li><a class="dropdown-item" href="/plugins">Show installed Plugins</a></li>
                                        <?php } ?>
                                        <li><a class="dropdown-item" href="/players">Show Online Players</a></li>
                                        <?php if (hasuserRole('admin')) { ?>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item" href="/admin/users">Admin: User Management</a></li> 
                                        <?php } ?>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="/">Home</a></li>
                                    </ul>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        <div class="d-lg-flex col-lg-3 justify-content-lg-end">
                            <?php
                            if (isLoggedIn()) {
                                ?>
                                <a href="/logout"><button type="button" class="btn btn-outline-danger">Logout</button></a>
                                <?php
                            } else {
                                ?>
                                <a href="/login"><button type="button" class="btn btn-outline-success">Login</button></a>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </nav>
        <?php } ?> 
        <?php if (isset($mainClass)) { ?>
            <main class="<?php echo $mainClass ?>">
            <?php } else { ?> 
                <main> 
                <?php } ?>
                <div class="container">
                    <div id="liveAlert"></div>
                </div>

                <?php if ($headerfooter) { ?>
                    <div class="container">
                    <?php } ?>
