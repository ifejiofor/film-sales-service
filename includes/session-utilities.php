<?php
/*
 * This file contains definitions of functions that interact directly with the PHP SESSION cache.
 */

// Resume the SESSION cache.
session_start() or die('Unable to resume SESSION cache.');


// Logs a given customer in.
function logCustomerIn($customerId)
{
    if (getCustomerInDatabase($customerId) != NULL) {
        $_SESSION['logged_in_customer_id'] = $customerId;
        syncSessionCartWithDatabaseCart();
    }
}


// Returns true if a customer is logged-in, returns false otherwise.
function customerIsLoggedIn()
{
    return isset($_SESSION['logged_in_customer_id']);
}


// Returns the ID of the customer who is logged-in.
function getIdOfLoggedInCustomer()
{
    return $_SESSION['logged_in_customer_id'];
}


// Logs any logged-in user out.
function logOut()
{
    unset($_SESSION['logged_in_customer_id']);
}


// Returns the appropriate position in the 'session cart' where a given film should be added into.
function getPositionInSessionCartWhereFilmShouldBeAdded($filmId)
{
    settype($filmId, 'integer');
    $position = 0;

    while (isset($_SESSION['film_id_in_cart_' . $position]) && $_SESSION['film_id_in_cart_' . $position] != $filmId) {
        $position++;
    }

    return $position;
}


// Returns true if a given film is already at a given position in the 'session cart', returns false otherwise.
function filmIsAlreadyAtPositionInSessionCart($filmId, $position)
{
    settype($filmId, 'integer');
    return isset($_SESSION['film_id_in_cart_' . $position]) && $_SESSION['film_id_in_cart_' . $position] == $filmId;
}


// Increments the quantity of films held at a given position in the 'session cart'.
function incrementFilmQuantityAtPositionInSessionCart($position)
{
    if (isset($_SESSION['film_quantity_in_cart_' . $position])) {
        $_SESSION['film_quantity_in_cart_' . $position] += 1;
    }
}


// Returns all films in the 'session cart'.
function getAllFilmsInSessionCart()
{
    $filmsInSessionCart = array();
    $film = array('film_id' => 0, 'film_quantity' => 0);

    for ($position = 0; isset($_SESSION['film_id_in_cart_' . $position]); $position++) {
        $film['film_id'] = $_SESSION['film_id_in_cart_' . $position];
        $film['film_quantity'] = $_SESSION['film_quantity_in_cart_' . $position];
        $filmsInSessionCart[] = $film;
    }

    return $filmsInSessionCart;
}


// Empties the 'session cart'.
function emptySessionCart()
{
    for ($position = 0; isset($_SESSION['film_id_in_cart_'. $position]); $position++) {
        unset($_SESSION['film_id_in_cart_'. $position]);
        unset($_SESSION['film_quantity_in_cart_'. $position]);
    }
}


// Adds a given film at a given position in the 'session cart', in a given quantity.
function addFilmAtPositionInSessionCart($filmId, $position, $quantity = 1) {
    $_SESSION['film_id_in_cart_' . $position] = ((integer) $filmId);
    $_SESSION['film_quantity_in_cart_' . $position] = ((integer) $quantity);
}
?>