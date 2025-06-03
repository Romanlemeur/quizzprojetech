<?php

/*
 *---------------------------------------------------------------
 * CODEIGNITER 4 BOOTSTRAP FILE
 *---------------------------------------------------------------
 *
 * The path to the front controller (this file) directory
 */
$pathsPath = realpath(FCPATH . '../app/Config/Paths.php');
if (! is_file($pathsPath)) {
    /*
     * The path hasn't been set properly. You can come back here and change this.
     */
    $pathsPath = FCPATH . '../app/Config/Paths.php';
}

/*
 * Location of the framework bootstrap file.
 */
require rtrim($pathsPath, '/ ') . DIRECTORY_SEPARATOR . 'Paths.php';

// Path to the front controller (this file)
// FCPATH is always added to this
$paths = new Config\Paths();

// Location of the framework bootstrap file.
$bootstrap = rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';
$app       = require realpath($bootstrap) ?: $bootstrap;

/*
 *---------------------------------------------------------------
 * LAUNCH THE APPLICATION
 *---------------------------------------------------------------
 * Now that everything is set up, it's time to actually fire
 * up the engines and make this app do its thang.
 */

$app->run(); 