<?php
// Database connection code
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lmsnew";

// Create a new connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the member ID from the URL parameter
$memberId = $_GET['id'];

// Function to handle fine payment and removal of book details from the table
function removeBookDetails($conn, $memberId) {
    // Get the email of the member and the reserved book using the member ID
    $getReservationDetailsSql = "SELECT stu_email, title, fine FROM bookreserve WHERE id = $memberId";
    $reservationDetailsResult = $conn->query($getReservationDetailsSql);

    if ($reservationDetailsResult->num_rows > 0) {
        $row = $reservationDetailsResult->fetch_assoc();
        $userEmail = $row['stu_email'];
        $reservedBook = $row['title'];
        $fine = $row['fine'];

        // Check if the fine is paid or not (assuming the payment is successful, you can add payment logic here)
        if ($fine > 0) {
            // Fine is paid, remove the book details from the "bookreserve" table
            $removeBookSql = "DELETE FROM bookreserve WHERE id = $memberId";

            if ($conn->query($removeBookSql) === TRUE) {
                echo "Fine paid and book details removed successfully!";
            } else {
                echo "Error removing book details: " . $conn->error;
            }
        } else {
            echo "Fine is not paid!";
        }
    } else {
        echo "Reservation details not found!";
    }
}

// Call the function to handle fine payment and removal of book details
removeBookDetails($conn, $memberId);

// Close the database connection
$conn->close();
?>
