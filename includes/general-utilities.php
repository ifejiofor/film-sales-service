<?php
/*
 * This file contains definitions of functions that perform general utility tasks.
 */

// Returns the re-formatted copy of a given value after re-formatting it as money.
function monetize($value)
{
    settype($value, 'string');

    $money = '';
    $count = 0;

    for ($i = strlen($value) - 1; $i >= 0; $i--) {
        if ($count == 3) {
            $money = ',' . $money;
            $count = 0;
        }

        $money = $value[$i] . $money;
        $count++;
    }

    return '<span class="naira-symbol"></span>' . $money;
}


// Returns true if a given film ID is valid, returns false otherwise.
function filmIdIsValid($filmId)
{
    return getFilmInDatabase($filmId) != NULL;
}


// Syncs the 'session cart' with the 'database cart'.
function syncSessionCartWithDatabaseCart()
{
    $filmsInSessionCart = getAllFilmsInSessionCart();

    if (count($filmsInSessionCart) > 0) {
        copySessionCartIntoDatabaseCartOfLoggedInCustomer();
    }
    else {
        copyDatabaseCartOfLoggedInCustomerIntoSessionCart();
    }
}


// Copies the 'session cart' into the 'database cart' of the logged-in customer.
function copySessionCartIntoDatabaseCartOfLoggedInCustomer()
{
    emptyDatabaseCartOfLoggedInCustomer();
    $filmsInSessionCart = getAllFilmsInSessionCart();

    foreach ($filmsInSessionCart as $film) {
        addFilmToDatabaseCartOfLoggedInCustomer($film['film_id'], $film['film_quantity']);
    }
}


// Copies the 'database cart' of the logged-in user into the 'session cart'.
function copyDatabaseCartOfLoggedInCustomerIntoSessionCart()
{
    emptySessionCart();
    $filmsInDatabaseCart = getAllFilmsInDatabaseCartOfLoggedInCustomer();
    $position = 0;

    foreach ($filmsInDatabaseCart as $film) {
        addFilmAtPositionInSessionCart($film['film_id'], $position, $film['film_in_cart_quantity']);
        $position++;
    }
}


// Returns true if a form has been submitted, returns false otherwise.
function formHasBeenSubmitted()
{
    return $_POST;
}


// Checks for errors in a submitted form. Returns an array containing appropriate error messages if error is found, returns NULL otherwise.
function checkForErrorInSubmittedForm()
{
    if (!formHasBeenSubmitted()) {
        return NULL;
    }

    $errorMessages = array();

    if (isset($_POST['email'])) {
        $errorMessages['email'] = checkForErrorInEmail($_POST['email']);
    }
    
    if (isset($_POST['password'])) {
        $errorMessages['password'] = checkForErrorInPassword($_POST['password']);
    }

    if (isset($_POST['firstname'])) {
        $errorMessages['firstname'] = checkForErrorInName($_POST['firstname']);
    }

    if (isset($_POST['lastname'])) {
        $errorMessages['lastname'] = checkForErrorInName($_POST['lastname']);
    }

    if (isset($_POST['genre'])) {
        $errorMessages['genre'] = checkForErrorInName($_POST['genre']);
    }

    if (isset($_POST['price'])) {
        $errorMessages['price'] = checkForErrorInNumber($_POST['price']);
    }

    if (isset($_POST['credit_card_number'])) {
        $errorMessages['credit_card_number'] = checkForErrorInNumber($_POST['credit_card_number']);
    }

    if (isset($_POST['date'])) {
        $errorMessages['date'] = checkForErrorInDate($_POST['date']);
    }

    if (isset($_POST['address'])) {
        $errorMessages['address'] = checkForErrorInAddress($_POST['address']);
    }

    $atLeastOneErrorHasBeenFound = false;

    foreach ($errorMessages as $error) {
        if ($error != NULL) {
            $atLeastOneErrorHasBeenFound = true;
            break;
        }
    }

    if ($atLeastOneErrorHasBeenFound) {
        return $errorMessages;
    }
    else {
        return NULL;
    }
}


// Checks for errors in an email. Returns appropriate error message if an error is found, returns false otherwise.
function checkForErrorInEmail($email)
{
    $errorMessage = NULL;

    if (empty($email)) {
        $errorMessage = 'You need to fill this field';
    }
    else if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
        $errorMessage = 'Invalid email address';
    }
    else if (basename($_SERVER['PHP_SELF']) == 'login.php' && getCustomerInDatabase($email) == NULL) {
        $errorMessage = 'This email does not belong to any of our customers. If you want to register this email, click on <a href="register.php' . (isset($_GET['page']) ? '?page=' . $_GET['page'] : '') . '">Register</a>';
    }
    else if (basename($_SERVER['PHP_SELF']) == 'register.php' && getCustomerInDatabase($email) != NULL) {
        $errorMessage = 'This email is already registered. If it is your email then <a href="login.php' . (isset($_GET['page']) ? '?page=' . $_GET['page'] : '') . '">click here</a> to login instead. If not enter another email';
    }

    return $errorMessage;
}


// Checks for errors in a password. Returns appropriate error message if an error is found, returns false otherwise.
function checkForErrorInPassword($password)
{
    $errorMessage = NULL;

    if (empty($password)) {
        $errorMessage = 'You need to fill this field';
    }
    else if (basename($_SERVER['PHP_SELF']) == 'login.php' && checkForErrorInEmail($_POST['email']) == NULL) {
        $customer = getCustomerInDatabase($_POST['email']);

        if (password_verify($password, $customer['customer_password']) == false) {
            $errorMessage = 'Incorrect password';
        }
    }

    return $errorMessage;
}


// Checks for errors in a name. Returns appropriate error message if an error is found, returns false otherwise.
function checkForErrorInName($name)
{
    $errorMessage = NULL;

    if (empty($name)) {
        $errorMessage = 'You need to fill this field';
    }
    else if (preg_match('/^[a-zA-Z]+$/', $name) == false) {
        $errorMessage = 'Invalid name';
    }

    return $errorMessage;
}


// Checks for errors in a number. Returns appropriate error message if an error is found, returns false otherwise.
function checkForErrorInNumber($number)
{
    $errorMessage = NULL;

    if (empty($number)) {
        $errorMessage = 'You need to fill this field';
    }
    else if (preg_match('/^[0-9]+$/', $number) == false) {
        $errorMessage = 'Invalid number';
    }

    return $errorMessage;
}


// Checks for errors in a date. Returns appropriate error message if an error is found, returns false otherwise.
function checkForErrorInDate($date)
{
    $errorMessage = NULL;

    if (empty($date)) {
        $errorMessage = 'You need to fill this field';
    }

    return $errorMessage;
}


// Checks for errors in an address. Returns appropriate error message if an error is found, returns false otherwise.
function checkForErrorInAddress($address)
{
    $errorMessage = NULL;

    if (empty($address)) {
        $errorMessage = 'You need to fill this field';
    }
    else if (preg_match('/^[a-zA-Z0-9 ,.-]+$/', $address) == false) {
        $errorMessage = 'There is an invalid symbol in this address. Please use only the following symbols ,.-';
    }

    return $errorMessage;
}
?>