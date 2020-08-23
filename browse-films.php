<?php
/*
 * This page displays a list of films that are available for purchase.
 */

require_once 'includes/header.php';
$condition = 'WHERE 1 = 1';
$offset = isset($_GET['offset']) ? (integer) $_GET['offset'] : 0;
$filmsAvailableForPurchase = getMultipleFilmsInDatabase($condition, $offset);

?>
<div class="container">
<?php

foreach ($filmsAvailableForPurchase as $film) {
?>
    <div class="row well well-sm">
        <div class="col-sm-2">
            <img src="images/<?php echo $film['film_thumbnail_filename'] ?>" alt="<?php echo $film['film_title'] ?>" height="100" max-width="100%"/>
        </div>
        <div class="col-sm-8">
            <h2><?php echo $film['film_title'] ?></h2>
            <p><span>Genre:</span> <?php echo $film['film_genre'] ?></p>
            <p><?php echo monetize($film['film_price']) ?></p>
        </div>
        <div class="col-sm-2">
            <a href="view-film.php?film=<?php echo $film['film_id'] ?>" class="btn btn-default">View</a>
            <a href="add-to-cart.php?film=<?php echo $film['film_id'] ?>" class="btn btn-default">Purchase</a>
        </div>
    </div>
<?php
}

$thereArePreviousFilms = $offset >= COUNT_OF_FILMS_BROWSEABLE_AT_ONCE && count(getMultipleFilmsInDatabase($condition, $offset - COUNT_OF_FILMS_BROWSEABLE_AT_ONCE)) > 0;
$thereAreNextFilms = count(getMultipleFilmsInDatabase($condition, $offset + COUNT_OF_FILMS_BROWSEABLE_AT_ONCE)) > 0;

if ($thereArePreviousFilms) {
?>
    <a href="browse-films.php?offset=<?php echo $offset - COUNT_OF_FILMS_BROWSEABLE_AT_ONCE ?>"><span class="glyphicon glyphicon-chevron-left"></span> Previous |</a>
<?php
}

if ($thereAreNextFilms) {
?>
    <a href="browse-films.php?offset=<?php echo $offset + COUNT_OF_FILMS_BROWSEABLE_AT_ONCE ?>" class="pull-right">| Next <span class="glyphicon glyphicon-chevron-right"></span></a>
<?php
}

?>
</div>
<?php

require_once 'includes/footer.php';
?>