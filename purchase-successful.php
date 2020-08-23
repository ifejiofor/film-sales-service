<?php
/*
 * This page informs a customer that their purchase is successful. It is opened whenever checkout.php successfully finalizes a purchase.
 */

require_once 'includes/header.php';
?>
<div class="container">
    <div class="alert alert-success text-center">
        <h2 class="text-extra-large"><span class="glyphicon glyphicon-ok"></span></h2>
        <h3>Purchase Successful!</h3>
        <p class="text-medium">You can now proceed to enjoy your film.</p>
        <div class="padding-large">
            <a href="index.php" class="btn btn-primary btn-lg">OK</a>
        </div>
    </div>
</div>
<?php
require_once 'includes/footer.php';
?>