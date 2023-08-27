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

// Get the email of the member using the member ID
$getUserEmailSql = "SELECT stu_email, title FROM bookreserve WHERE id = $memberId";
$userEmailResult = $conn->query($getUserEmailSql);

if ($userEmailResult->num_rows > 0) {
    $row = $userEmailResult->fetch_assoc();
    $userEmail = $row['stu_email'];
    $reservedBook = $row['title'];

    // Update the reserved_date with the current date, due_date 14 days later, and set the status column to "Book Reserved"
    $currentDate = date("Y-m-d");
    $dueDate = date('Y-m-d', strtotime($currentDate . ' + 14 days'));
    
    $updateReservationSql = "UPDATE bookreserve SET reserved_date = '$currentDate', due_date = '$dueDate', status = 'Book Reserved' WHERE stu_email = '$userEmail' AND title = '$reservedBook'";
    
    if ($conn->query($updateReservationSql) === TRUE) {
        echo "Reserved date and status updated successfully!";
    } else {
        echo "Error updating reserved date and status: " . $conn->error;
    }
} else {
    echo "Member not found!";
}

// Close the database connection
$conn->close();
?>
