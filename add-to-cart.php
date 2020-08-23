<?php
/*
 * This page adds a given film to cart and displays a confirmation that the film has been added to cart.
 */

require_once 'includes/header.php';

if (!isset($_GET['film']) || !filmIdIsValid($_GET['film'])) {
    header('Location: index.php');
    die();
}

$position = getPositionInSessionCartWhereFilmShouldBeAdded($_GET['film']);

if (filmIsAlreadyAtPositionInSessionCart($_GET['film'], $position)) {
    incrementFilmQuantityAtPositionInSessionCart($position);
}
else {
    addFilmAtPositionInSessionCart($_GET['film'], $position);
}

if (customerIsLoggedIn()) {
    syncSessionCartWithDatabaseCart();
}

$film = getFilmInDatabase($_GET['film']);
?>
    <div class="container">
        <div class="alert alert-success text-center">
            <h2 class="text-extra-large"><span class="glyphicon glyphicon-ok"></span></h2>
            <h3>Added to Cart</h3>
            <p text="text-medium"><img src="images/<?php echo $film['film_thumbnail_filename'] ?>" height="50" width="50"/> <?php echo $film['film_title'] ?> has been successfully added to cart.</p>
            <div style="padding: 3em;">
                <a href="browse-films.php" class="btn btn-warning btn-lg"><span class="glyphicon glyphicon-chevron-left"></span> Browse More Films</a>
                <a href="view-cart.php" class="btn btn-primary btn-lg">Proceed to Checkout <span class="glyphicon glyphicon-chevron-right"></span></a>
            </div>
        </div>
    </div>
<?php
require_once 'includes/footer.php';
?>