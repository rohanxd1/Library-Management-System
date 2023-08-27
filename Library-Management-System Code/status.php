<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Reservation Status</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .status-heading {
            text-align: center;
        }
        .container{
            padding-top:1%;
        }
        #video-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            object-fit: cover;
        }
    </style>
</head>
<body>
<video id="video-background" autoplay muted loop>
    <source src="video/tech.mp4" type="video/mp4">
</video>
<h1 class="status-heading" style="color: white; margin-top: 10px;">Status Page</h1>
<?php
session_start();
$db_host = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "lmsnew";

// Create Connection
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_SESSION['userEmail'])) {
    $userEmail = $_SESSION['userEmail'];

    // Fetch the user's name from memberadd table using the email
    $sql = "SELECT stu_name FROM memberadd WHERE stu_email='$userEmail'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $userName = $row['stu_name'];
    }
}

// Fetch book reservation details for the user
$sqlBookReserve = "SELECT id, title, author, status, reserved_date, due_date, fine FROM bookreserve WHERE stu_email='$userEmail'";
$resultBookReserve = $conn->query($sqlBookReserve);

echo '<div class="container">';
echo '<table class="table table-striped">';
echo '<thead>';
echo '<tr>';
echo '<th>Title</th>';
echo '<th>Author</th>';
echo '<th>Status</th>';
echo '<th>Reserved Date</th>';
echo '<th>Due Date</th>';
echo '<th>Fine</th>';
echo '<th>Pay</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

if ($resultBookReserve->num_rows > 0) {
    while ($rowBookReserve = $resultBookReserve->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $rowBookReserve['title'] . '</td>';
        echo '<td>' . $rowBookReserve['author'] . '</td>';

        if ($rowBookReserve['reserved_date'] == '0000-00-00') {
            echo '<td>' . $rowBookReserve['status'] . '</td>';
            echo '<td>Book not yet reserved</td>';
            echo '<td>Book not yet reserved</td>';
            echo '<td></td>';
            echo '<td></td>';
        } else {
            echo '<td>' . $rowBookReserve['status'] . '</td>';
            echo '<td>' . $rowBookReserve['reserved_date'] . '</td>';

            if ($rowBookReserve['due_date'] == '0000-00-00') {
                echo '<td>Book not yet reserved</td>';
            } else {
                echo '<td>' . $rowBookReserve['due_date'] . '</td>';
                $dueDate = new DateTime($rowBookReserve['due_date']);
                $currentDate = new DateTime();
                if ($currentDate > $dueDate) {
                    $fineAmount = 350 + ($currentDate->diff($dueDate)->days * 10);

                    // Update the fine column in the bookreserve table
                    $bookReserveId = $rowBookReserve['id'];
                    $updateFineQuery = "UPDATE bookreserve SET fine = $fineAmount WHERE id = $bookReserveId";
                    $conn->query($updateFineQuery);

                    echo '<td>Due Amount: ' . $fineAmount . '</td>';
                    if ($fineAmount > 0) {
                        echo '<td><a href="pay.php?fine_id=' . $bookReserveId . '&amount=' . $fineAmount . '" class="btn btn-primary">Pay</a></td>';

                    } else {
                        echo '<td></td>';
                    }
                } else {
                    echo '<td>No Fine</td>';
                    echo '<td></td>';
                }
            }
        }

        echo '</tr>';
    }
} else {
    echo '<tr>';
    echo '<td colspan="7">No reservations found</td>';
    echo '</tr>';
}

echo '</tbody>';
echo '</table>';
echo '</div>';

$conn->close();
?>

<div class="text-center">
    <a href="memberpage.php" class="btn btn-dark mt-2">Return to User Page</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
