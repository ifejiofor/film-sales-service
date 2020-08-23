<?php
/*
 * This page informs a customer that their registration is successful. It is opened whenever register.php successfully registers a customer.
 */

require_once 'includes/header.php';
?>
<div class="container">
    <div class="alert alert-success text-center">
        <h2 class="text-extra-large"><span class="glyphicon glyphicon-ok"></span></h2>
        <h3>Registration Successful!</h3>
        <div class="padding-large">
            <a href="login.php<?php echo isset($_GET['page']) ? '?page=' . $_GET['page'] : '' ?>" class="btn btn-primary btn-lg">Proceed to Login Page</a>
        </div>
    </div>
</div>
<?php
require_once 'includes/footer.php';
?>