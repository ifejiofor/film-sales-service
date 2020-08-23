<?php
/*
 * This page displays all films that have been added to cart.
 */

require_once 'includes/header.php';
$allFilmsInCart = getAllFilmsInSessionCart();

if (sizeof($allFilmsInCart) == 0) {
?>
    <p class="text-center">Cart is empty. Click <a href="browse-films.php" class="btn btn-default">here</a> to add films to cart.</p>
<?php
}
else {
?>
    <div class="container table-responsive">
        <h2>Cart</h2>

        <table class="table">
            <thead>
                <tr>
                    <th>Thumbnail</th>
                    <th>Title</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Quantity</th>
                    <th class="text-right">Total</th>
                    <th></th>
                </tr>
            </thead>
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
                    <td class="text-right"><?php echo $filmInCart['film_quantity'] ?></td>
                    <td class="text-right"><?php echo monetize($totalPrice) ?></td>
                    <td class="text-right"><a href="delete-from-cart.php?film=<?php echo $correspondingFilmInDatabase['film_id'] ?>"><span class="glyphicon glyphicon-trash"></span></a></td>
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
                    <td></td>
                </tr>
            </tfoot>
        </table>

        <p class="text-center"><a href="checkout.php" class="btn btn-primary btn-lg btn-block">Checkout</a></p>
    </div>
<?php
}

require_once 'includes/footer.php';
?>