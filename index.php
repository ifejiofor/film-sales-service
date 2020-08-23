<?php
/*
 * This is the index page of the film sales service. It merely redirects to browse-films.php. It can also perform log out action if required.
 */

if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    require_once 'includes/session-utilities.php';
    logOut();
}

header('Location: browse-films.php');
?>