<?php
/**
 * This file contains definitions of functions that interact directly with the film_sales_database.
 */

// Connect to the film_sales_database and get a handle to the database.
$databaseHandle = mysqli_connect('localhost', 'root', '', 'film_sales_database') or die('Unable to establish database connection.');


// Returns details of multiple films that satisfies a given condition in the database, starting at a given offset.
function getMultipleFilmsInDatabase($condition = 'WHERE 1 = 1', $offset = 0)
{
    global $databaseHandle;
    $query = 'SELECT * FROM films ' . mysqli_real_escape_string($databaseHandle, $condition) . ' ORDER BY film_title LIMIT ' . ((integer) $offset) . ', ' . COUNT_OF_FILMS_BROWSEABLE_AT_ONCE;
    return getMultipleRowsOfResult($databaseHandle, $query);
}


// Returns details of the film that has a given ID in the database.
function getFilmInDatabase($filmId)
{
    global $databaseHandle;
    $query = 'SELECT * FROM films WHERE film_id = ' . ((integer) $filmId);
    return getSingleRowOfResult($databaseHandle, $query);
}


// Returns details of the logged-in customer.
function getDetailsOfLoggedInCustomer()
{
    return getCustomerInDatabase(getIdOfLoggedInCustomer());
}


// Returns details of the customer that has a given ID in the database.
function getCustomerInDatabase($customerId)
{
    global $databaseHandle;
    $query = 'SELECT * FROM customers WHERE customer_email = "' . mysqli_real_escape_string($databaseHandle, $customerId) . '"';
    return getSingleRowOfResult($databaseHandle, $query);
}


// Updates details of the logged-in customer.
function updateDetailsOfLoggedInCustomer($newFirstname, $newLastname, $newDateOfBirth, $newAddress, $newCreditCardNumber)
{
    global $databaseHandle;
    $query = 'UPDATE customers SET
        customer_firstname = "' . mysqli_real_escape_string($databaseHandle, $newFirstname) . '",
        customer_lastname = "' . mysqli_real_escape_string($databaseHandle, $newLastname) . '",
        customer_date_of_birth = "' . mysqli_real_escape_string($databaseHandle, $newDateOfBirth) . '",
        customer_address = "' . mysqli_real_escape_string($databaseHandle, $newAddress) . '",
        customer_credit_card_number = "' . mysqli_real_escape_string($databaseHandle, $newCreditCardNumber) . '"
        WHERE customer_email = "' . getIdOfLoggedInCustomer() . '"';
    mysqli_query($databaseHandle, $query);
}


// Adds a new customer to the database.
function addNewCustomerToDatabase($email, $password, $firstname, $lastname, $dateOfBirth)
{
    global $databaseHandle;
    $query = 'INSERT INTO customers (customer_email, customer_password, customer_firstname, customer_lastname, customer_date_of_birth)
        VALUES ("' . mysqli_real_escape_string($databaseHandle, $email) . '", "' . password_hash($password, PASSWORD_DEFAULT) . '", "' . mysqli_real_escape_string($databaseHandle, $firstname) . '", "' . mysqli_real_escape_string($databaseHandle, $lastname) . '", "' . mysqli_real_escape_string($databaseHandle, $dateOfBirth) . '")';
    mysqli_query($databaseHandle, $query);
}


// Returns all films in the 'database cart' of the logged-in customer. These are films that were added to cart by the logged-in customer and are, thus, held in the database as such.
function getAllFilmsInDatabaseCartOfLoggedInCustomer()
{
    global $databaseHandle;
    $query = 'SELECT * FROM films_in_cart WHERE customer_email = "' . getIdOfLoggedInCustomer() . '"';
    return getMultipleRowsOfResult($databaseHandle, $query);
}


// Empties the 'database cart' of the logged-in customer.
function emptyDatabaseCartOfLoggedInCustomer()
{
    global $databaseHandle;
    $query = 'DELETE FROM films_in_cart WHERE customer_email = "' . getIdOfLoggedInCustomer() . '"';
    mysqli_query($databaseHandle, $query);
}


// Adds a given film, in a given quantity, into the 'database cart' of the logged-in customer.
function addFilmToDatabaseCartOfLoggedInCustomer($filmId, $quantity)
{
    global $databaseHandle;
    $query = 'INSERT INTO films_in_cart (customer_email, film_id, film_in_cart_quantity) VALUES ("' . getIdOfLoggedInCustomer() . '", ' . $filmId . ', ' . $quantity . ')';
    mysqli_query($databaseHandle, $query);
}


// Adds some given films into the purchase record of the logged-in customer.
function addFilmsToPurchaseRecordOfLoggedInCustomer($arrayOfFilms)
{
    global $databaseHandle;
    $query = 'INSERT INTO purchases (purchase_date, purchase_time, customer_email) VALUES (DATE(NOW()), TIME(NOW()), "' . getIdOfLoggedInCustomer() . '")';
    $result = mysqli_query($databaseHandle, $query);

    if ($result === true) {
        $purchaseId = mysqli_insert_id($databaseHandle);
        $query = 'INSERT INTO films_purchased (purchase_id, film_id, film_purchased_quantity) VALUES';

        foreach ($arrayOfFilms as $film) {
            $query .= ' (' . $purchaseId . ', ' . $film['film_id'] . ', ' . $film['film_quantity'] . '),';
        }

        $query[strlen($query) - 1] = ' ';
        mysqli_query($databaseHandle, $query);
    }
}


// Queries the database and returns an array that contains rows of the result of the query. Useful for queries that return more than one row.
function getMultipleRowsOfResult($databaseHandle, $query)
{
    $result = mysqli_query($databaseHandle, $query);

    if ($result == NULL || mysqli_num_rows($result) == 0) {
        return array();
    }

    $multipleRowsOfResult = array();
    $row = mysqli_fetch_assoc($result);

    while ($row != NULL) {
        $multipleRowsOfResult[] = $row;
        $row = mysqli_fetch_assoc($result);
    }

    return $multipleRowsOfResult;
}


// Queries the database and returns the first row of the result of the query. Useful for queries that return only one row.
function getSingleRowOfResult($databaseHandle, $query)
{
    global $databaseHandle;
    $result = mysqli_query($databaseHandle, $query);

    if ($result == NULL || mysqli_num_rows($result) == 0) {
        return NULL;
    }
    else {
        return mysqli_fetch_assoc($result);
    }
}
?>