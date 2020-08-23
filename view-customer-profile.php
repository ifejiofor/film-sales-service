<?php
/*
 * This page displays the profile details of the logged-in customer.
 */

require_once 'includes/header.php';

if (!customerIsLoggedIn()) {
    header('Location: index.php');
    die();
}

$profileDetails = getDetailsOfLoggedInCustomer();
?>
<div class="container">
    <div class="margin-bottom-large">
        <h2>Profile - <?php echo $profileDetails['customer_firstname'] . ' ' . $profileDetails['customer_lastname'] ?></h2>
        <a href="edit-customer-profile.php" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span> Edit</a>
    </div>

    <table class="table">
        <tr>
            <th>Email:</th>
            <td><?php echo $profileDetails['customer_email'] ?></td>
        </tr>
        <tr>
            <th>Date of Birth:</th>
            <td><?php echo $profileDetails['customer_date_of_birth'] ?></td>
        </tr>
        <tr>
            <th>Contact Address:</th>
            <td><?php echo $profileDetails['customer_address'] ?></td>
        </tr>
        <tr>
            <th>Credit Card Number:</th>
            <td><?php echo $profileDetails['customer_credit_card_number'] ?></td>
        </tr>
    </table>
</div>
<?php
require_once 'includes/footer.php';
?>