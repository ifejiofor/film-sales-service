<?php
/*
 * This page displays the login form, validates any data submitted in the form, and logs a given customer in if valid login details were submitted.
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
        logCustomerIn($_POST['email']);
        header('Location: ' . (isset($_GET['page']) ? $_GET['page'] : 'index.php'));
        die();
    }
}
?>
<div class="container">
    <h2><?php echo isset($_GET['message']) && $_GET['message'] == 'compulsive' ? 'You Must Log In To Continue' : 'Log In' ?></h2>

    <form method="POST" action="<?php echo htmlspecialchars(basename($_SERVER['PHP_SELF'])) . (isset($_GET['page']) ? ('?page=' . $_GET['page']) : '') ?>" role="form">
        <div class="form-group">
            <label for="email">Email address:</label>
            <input type="email" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : '' ?>" max-length="100" class="form-control" id="email"/>
            <span class="error-message"><?php echo $errorMessages == NULL ? '' : $errorMessages['email'] ?></span>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" max-length="100" class="form-control" id="password"/>
            <span class="error-message"><?php echo $errorMessages == NULL ? '' : $errorMessages['password'] ?></span>
        </div>
        <div class="form-group">
            <p>Don't have an account? <a href="register.php<?php echo isset($_GET['page']) ? '?page=' . $_GET['page'] : '' ?>">Click here</a> to register.</p>
        </div>
        <button type="submit" class="btn btn-success">Submit</button>
    </form>
</div>
<?php
require_once 'includes/footer.php';
?>