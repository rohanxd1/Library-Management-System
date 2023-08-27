

<!DOCTYPE html>
<html>
<head>
    <title>Reservation Details</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 50px;
        }
        h1 {
            padding-top: 25px;
            text-align: center;
            color: white;
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
        
        .btn-reserved {
            width: 120px;
            margin-right: 5px;
            border-radius: 30px;
            background-color: #007bff;
            color: white;
            border: none;
        }
        .btn-reserved:hover {
            background-color: #0056b3;
            cursor: pointer;
            color: white;
        }
        .btn-paid {
            width: 120px;
            margin-right: 5px;
            border-radius: 30px;
            background-color: #dc3545;
            color: white;
            border: none;
        }
        .btn-paid:hover {
            background-color: #b30023;
            cursor: pointer;
            color: white;
        }
        
        /* Additional styles for the table */
        table {
            font-size: 18px;
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            color: white;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: white;
            color: black;
        }
        .text-center .btn {
            background-color: black;
            color: white;
            padding: 10px 20px;
            border: 2px solid #4CAF50;
            border-radius: 30px;
            cursor: pointer;
        }

        .text-center .btn:hover {
            background-color: #222222;
            border-color: #45a049;
            color: white;
        }
        
    </style>
</head>
<body>
    <video id="video-background" autoplay muted loop>
        <source src="video/details.mp4" type="video/mp4">
    </video>
    <h1>Reservation Details</h1>
    <div class="container">
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Email</th>
                    <th>Reserved Book</th>
                    <th>Actions</th>
                    <th>Status</th>
                    <th>Fine</th>
                    <th>Fine Status</th>
                </tr>
            </thead>
            <tbody>
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

                // Function to handle fine payment and removal of book details from the table
                function removeBookDetails($conn, $memberId) {
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

                // Retrieve reservation details from the bookreserve table
                $sql = "SELECT * FROM bookreserve";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Loop through each reservation and display the details in the table
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $row['stu_email'] . '</td>';
                        echo '<td>' . $row['title'] . '</td>';
                        echo '<td>';
                        echo '<button class="btn btn-reserved btn-sm" onclick="updateReservation('.$row['id'].')">Reserved</button>';
                        echo '<button class="btn btn-paid btn-sm" onclick="removeBookDetails('.$row['id'].')">Paid</button>';
                        echo '</td>';
                        echo '<td>' . $row['status'] . '</td>';
                        echo '<td>' . $row['fine'] . '</td>';

                        // Display Fine Status based on conditions
                        echo '<td>';
                        if ($row['fine'] == 0) {
                            // Fine is 0, display blank
                            echo '';
                        } else if ($row['fine'] != '' && $row['fine_status'] == '') {
                            // Fine is not blank and Fine Status is blank, display "Payment not yet done"
                            echo 'Payment not yet done';
                        } else if ($row['fine'] != '' && $row['fine_status'] == 'Fine Paid') {
                            // Fine is not blank and Fine Status is "Fine Paid", display "Fine Paid"
                            echo 'Fine Paid';
                        }
                        echo '</td>';

                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="6">No reservation details found.</td></tr>';
                }

                // Close the database connection
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function updateReservation(memberId) {
            // AJAX request to update the reservation details
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    alert(this.responseText);
                    location.reload(); // Refresh the page to see the updated details
                }
            };
            xhttp.open("GET", "reserveconfirmation.php?id=" + memberId, true);
            xhttp.send();
        }

        // Function to handle fine payment and removal of book details from the table
        function removeBookDetails(memberId) {
            // AJAX request to remove book details from the "bookreserve" table
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    alert(this.responseText);
                    location.reload(); // Refresh the page to see the updated details
                }
            };
            xhttp.open("GET", "removebook.php?id=" + memberId, true);
            xhttp.send();
        }
    </script>
    <div class="text-center">
    <a href="view_members.php" class="btn btn-dark mt-2">Return to Admin Page</a>
</div>
</body>
</html>
