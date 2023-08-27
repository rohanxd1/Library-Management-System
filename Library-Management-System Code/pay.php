<?php
if (isset($_POST['fine_paid'])) {
    $fineId =$_GET['fine_id'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "lmsnew";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "UPDATE bookreserve SET fine_status = 'Fine Paid' WHERE id = $fineId";
    if ($conn->query($sql) === TRUE) {
        // Display the message
        echo '<p>Payment successful!!  Fine status would be updated by the admin...</p>';
        echo '<script>setTimeout(function(){ window.location.href = "memberpage.php"; }, 2000);</script>';
        exit(); // Stop executing further PHP code
    } else {
        echo "Error updating record: " . $conn->error;
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay Fine</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            text-align: center;
        }
        .pay-heading {
            margin-top: 20px;
            font-size: 50px;
            color: aliceblue;
        }
        .pay-image {
            display: block;
            margin: 40px auto;
            max-width: 400px;
            max-height: 400px;
        }
        .pay-amount {
            font-size: 25px;
            color: aliceblue;
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
        .fine-paid-button {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<video id="video-background" autoplay muted loop>
    <source src="video/tech.mp4" type="video/mp4">
</video>
<div class="container">
    <h1 class="pay-heading">Pay Fine</h1>
    <img class="pay-image" src="image/pay.jpg" alt="Fine Image">
    <p class="pay-amount">Pay Fine: <?php echo $_GET['amount']; ?></p>
    <div class="text-center">
        <form action="" method="POST">
            <button type="submit" name="fine_paid" class="btn btn-dark mt-2">Fine Paid</button>
        </form>
    </div>
</div>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
