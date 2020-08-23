<?php
define('COUNT_OF_FILMS_BROWSEABLE_AT_ONCE', 3);
require_once 'session-utilities.php';
require_once 'database-utilities.php';
require_once 'general-utilities.php';
?>

<!DOCTYPE html>

<html>
    <head>
        <title>Film Sales Service</title>
        <link href="bootstrap-files/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="stylesheets/style.css" rel="stylesheet" type="text/css"/>
    </head>

    <body>
        <header class="navbar navbar-inverse">
            <nav class="container-fluid">
                <h1 class="navbar-header">
                    <a href="index.php" class="navbar-brand">Film Sales Service</a>
                </h1>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="view-cart.php"><span class="glyphicon glyphicon-shopping-cart"></span> Cart</a></li>
<?php
if (customerIsLoggedIn()) {
?>
                    <li><a href="view-customer-profile.php"><span class="glyphicon glyphicon-user"></span> My Profile</a></li>
                    <li><a href="index.php?action=logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
<?php
}
else {
?>
                    <li><a href="login.php?page=<?php echo htmlspecialchars(basename($_SERVER['PHP_SELF'])) ?>"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
<?php
}
?>
                </ul>
            </nav>
        </header>
