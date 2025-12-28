<?php

/*
 * The include file has to contain the following values:
 *   Array('filename' => string, -- Filename for template
 *         'data' => Array())    -- Array with data for the template
 *         'title' => string,    -- Title of the page
 *         'header-footer' => boolean, -- true if not set
 *         'custom-css' => string, -- Filename for a custom css, design.css if not set
 *         'additional-css' => string, -- Filename for an additional css to the main css
 * - At an exception 
 *   string  -- Errormessage.
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'functions.php';
require_once 'config.php';
require_once 'sites.php';

date_default_timezone_set('Europe/Berlin');

createRequiredFolders();

session_start();

$db = new PDO('sqlite:' . __DIR__ . '/../data/users.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

setupDb($db);

$headerfooter = true;
$title = 'Spigot - Webinterface';
$page = 'main';

//check if specific page was accessed
if (isset($_GET['page'])) {
    $page = strtolower(trim($_GET['page'], "/"));
    if (!isset($files[$page])) {
        header("Location: /notfound");
        die();
    }
}

//check if page string actually exists in sites.php
if (isset($files[$page])) {
    if (file_exists('includes/' . $files[$page])) {
        $ret = include 'includes/' . $files[$page];
    } else {
        include 'templates/header.php';
        showError("Es wurde keine Include Datei gefunden: 'includes/" . $files[$page] . "'");
        include 'templates/footer.php';
        return;
    }
} else {
    header("Location: /notfound");
    die();
}


//Search for title and main-design, has to happen before header template gets included
if (is_array($ret)) {
    if (isset($ret['title'])) {
        $title = $ret['title'];
    }
    if (isset($ret['header-footer'])) {
        $headerfooter = $ret['header-footer'];
    }
    if (isset($ret['custom-css'])) {
        $css = $ret['custom-css'];
    }
    if (isset($ret['custom-js'])) {
        $customjs = $ret['custom-js'];
    }
    if (isset($ret['body-class'])) {
        $bodyClass = $ret['body-class'];
    }
    if (isset($ret['main-class'])) {
        $mainClass = $ret['main-class'];
    }
} else if (is_string($ret)) {
    $title = 'Error';
}

//include header template
include 'templates/header.php';

//check if return array is correctly
if (is_array($ret) && isset($ret['filename'], $ret['data']) && is_string($ret['filename']) && is_array($ret['data'])) {
    //check if given template file exists
    if (file_exists($file = 'templates/' . $ret['filename'])) {
        $data = $ret['data'];
        //include template file
        include $file;
    } else {
        //template file not found
        showError('Template "' . $file . '" wurde nicht gefunden.');
    }
} else if (is_string($ret)) {
    //include returns a string -> display as error message
    showError($ret);
} else if (1 === $ret) {
    //include does not return anything
    showError("Die Include Datei Returnt nichts!");
} else {
    //include returns something we don't handle (like boolean for ex)
    showError("Die Include Datei Returnt nicht das was das System erwartet.");
}

//include footer template
include 'templates/footer.php';
?>