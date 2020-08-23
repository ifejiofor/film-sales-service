<?php
/*
 * This page displays the details of a given film.
 */

require_once 'includes/header.php';

if (!isset($_GET['film']) || !filmIdIsValid($_GET['film'])) {
    header('Location: index.php');
    die();
}

$film = getFilmInDatabase($_GET['film']);
?>
    <div class="container">
        <div class="jumbotron text-center">
            <div class="container"><a href="browse-films.php" class="pull-left"><span class="glyphicon glyphicon-chevron-left"></span> Back</a></div>
            <div><img src="images/<?php echo $film['film_thumbnail_filename'] ?>" height="250" width="250"/></div>
            <h2><?php echo $film['film_title'] ?></h2>
            <h3><span>Genre:</span> <?php echo $film['film_genre'] ?></h3>
            <h4><span>Price:</span> <?php echo monetize($film['film_price']) ?></h4>
            <p><a href="add-to-cart.php?film=<?php echo $film['film_id'] ?>" class="btn btn-primary btn-lg">Purchase</a></p>
        </div>
    </div>
<?php
require_once 'includes/footer.php';
?>