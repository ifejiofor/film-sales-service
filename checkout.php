<?php
/*
 * This page allows the customer to finalize the purchase of films they have added to cart.
 */

require_once 'includes/header.php';

if (!customerIsLoggedIn()) {
    header('Location: login.php?page=checkout.php&message=compulsive');
    die();
}

$allFilmsInCart = getAllFilmsInSessionCart();

if (count($allFilmsInCart) == 0) {
    header('Location: index.php');
    die();
}

$errorMessages = NULL;

if (formHasBeenSubmitted()) {
    $errorMessages = checkForErrorInSubmittedForm();

    if ($errorMessages == NULL) {
        updateDetailsOfLoggedInCustomer($_POST['firstname'], $_POST['lastname'], $_POST['date'], $_POST['address'], $_POST['credit_card_number']);
        addFilmsToPurchaseRecordOfLoggedInCustomer($allFilmsInCart);
        emptySessionCart();
        emptyDatabaseCartOfLoggedInCustomer();
        header('Location: purchase-successful.php');
        die();
    }
}
?>
<div class="container table-responsive">
    <h2 class="text-large">Checkout</h2>
    <p>Fill in your payment details to finalize the your purchase. It's as simple as that!</p>

    <table class="table table-striped">
        <tbody>
<?php
    $accumulatedTotalPrice = 0;

    foreach ($allFilmsInCart as $filmInCart) {
        $correspondingFilmInDatabase = getFilmInDatabase($filmInCart['film_id']);
        $totalPrice = $filmInCart['film_quantity'] * $correspondingFilmInDatabase['film_price'];
        $accumulatedTotalPrice += $totalPrice;
?>
            <tr>
                <td><img src="images/<?php echo $correspondingFilmInDatabase['film_thumbnail_filename'] ?>" height="50" width="50"/></td>
                <td><?php echo $correspondingFilmInDatabase['film_title'] ?></td>
                <td class="text-right"><?php echo monetize($correspondingFilmInDatabase['film_price']) ?></td>
                <td class="text-right">x <?php echo $filmInCart['film_quantity'] ?></td>
                <td class="text-right"><?php echo monetize($totalPrice) ?></td>
            </tr>
<?php
        
    }
?>
        </tbody>
        <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="text-right"><?php echo monetize($accumulatedTotalPrice) ?></td>
            </tr>
        </tfoot>
    </table>
<?php

$existingDetails = getDetailsOfLoggedInCustomer();

?>
    <form method="POST" action="<?php echo htmlspecialchars(basename($_SERVER['PHP_SELF'])) ?>" role="form">
        <h3>Payment Details</h3>
        <div class="form-group">
            <label for="firstname">First name:</label>
            <input type="text" name="firstname" value="<?php echo isset($_POST['firstname']) ? $_POST['firstname'] : $existingDetails['customer_firstname'] ?>" max-length="100" class="form-control" id="firstname"/>
            <span class="error-message"><?php echo $errorMessages == NULL ? '' : $errorMessages['firstname'] ?></span>
        </div>
        <div class="form-group">
            <label for="lastname">Last name:</label>
            <input type="text" name="lastname" value="<?php echo isset($_POST['lastname']) ? $_POST['lastname'] : $existingDetails['customer_lastname'] ?>" max-length="100" class="form-control" id="lastname"/>
            <span class="error-message"><?php echo $errorMessages == NULL ? '' : $errorMessages['lastname'] ?></span>
        </div>
        <div class="form-group">
            <label for="date">Date of birth:</label>
            <input type="date" name="date" value="<?php echo isset($_POST['date']) ? $_POST['date'] : $existingDetails['customer_date_of_birth'] ?>" class="form-control" id="date"/>
            <span class="error-message"><?php echo $errorMessages == NULL ? '' : $errorMessages['date'] ?></span>
        </div>
        <div class="form-group">
            <label for="address">Address:</label>
            <input type="text" name="address" value="<?php echo isset($_POST['address']) ? $_POST['address'] : $existingDetails['customer_address'] ?>" max-length="200" class="form-control" id="address"/>
            <span class="error-message"><?php echo $errorMessages == NULL ? '' : $errorMessages['address'] ?></span>
        </div>
        <div class="form-group">
            <label for="credit_card_number">Credit card number:</label>
            <input type="text" name="credit_card_number" value="<?php echo isset($_POST['credit_card_number']) ? $_POST['credit_card_number'] : $existingDetails['customer_credit_card_number'] ?>" max-length="100" class="form-control" id="credit_card_number"/>
            <span class="error-message"><?php echo $errorMessages == NULL ? '' : $errorMessages['credit_card_number'] ?></span>
        </div>
        <button type="submit" class="btn btn-success">Purchase</button>
    </form>
</div>
<?php
require_once 'includes/footer.php';
?>