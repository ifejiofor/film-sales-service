<?php
/*
 * This page allows a new customer to register to the film sales service.
 */

require_once 'includes/header.php';

if (customerIsLoggedIn()) {
    header('Location: index.php');
    die();
}

$errorMessages = NULL;

if (formHasBeenSubmitted()) {
    $errorMessages = checkForErrorInSubmittedForm();

    if ($errorMessages == NULL) {
        addNewCustomerToDatabase($_POST['email'], $_POST['password'], $_POST['firstname'], $_POST['lastname'], $_POST['date']);
        header('Location: registration-successful.php' . (isset($_GET['page']) ? '?page=' . $_GET['page'] : ''));
        die();
    }
}
?>
<div class="container">
    <h2>Register to the Film Sales Service</h2>
    <p>Join us now and begin and enjoy an unlimited collection of interesting films.</p>

    <form method="POST" action="<?php echo htmlspecialchars(basename($_SERVER['PHP_SELF'])) . (isset($_GET['page']) ? ('?page=' . $_GET['page']) : '') ?>" role="form">
        <div class="form-group">
            <label for="firstname">First name:</label>
            <input type="text" name="firstname" value="<?php echo isset($_POST['firstname']) ? $_POST['firstname'] : '' ?>" max-length="100" class="form-control" id="firstname"/>
            <span class="error-message"><?php echo $errorMessages == NULL ? '' : $errorMessages['firstname'] ?></span>
        </div>
        <div class="form-group">
            <label for="lastname">Last name:</label>
            <input type="text" name="lastname" value="<?php echo isset($_POST['lastname']) ? $_POST['lastname'] : '' ?>" max-length="100" class="form-control" id="lastname"/>
            <span class="error-message"><?php echo $errorMessages == NULL ? '' : $errorMessages['lastname'] ?></span>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="text" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : '' ?>" max-length="100" class="form-control" id="email"/>
            <span class="error-message"><?php echo $errorMessages == NULL ? '' : $errorMessages['email'] ?></span>
        </div>
        <div class="form-group">
            <label for="date">Date of birth:</label>
            <input type="date" name="date" value="<?php echo isset($_POST['date']) ? $_POST['date'] : '' ?>" class="form-control" id="date"/>
            <span class="error-message"><?php echo $errorMessages == NULL ? '' : $errorMessages['date'] ?></span>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" max-length="100" class="form-control" id="password"/>
            <span class="error-message"><?php echo $errorMessages == NULL ? '' : $errorMessages['password'] ?></span>
        </div>
        <button type="submit" class="btn btn-success">Submit</button>
    </form>
</div>
<?php
require_once 'includes/footer.php';
?>