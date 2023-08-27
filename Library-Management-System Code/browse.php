<!DOCTYPE html>
<html>
<head>
    <title>View Books</title>
    <style>
        #video-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            object-fit: cover;
        }
        

        .search {
            margin-bottom: 20px;
        }

        .books-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            color: #f2f2f2;
            font-weight: bold;
        }

        .books-table th,
        .books-table td {
            padding: 10px;
            text-align: left;
        }

        .books-table th {
            background-color: grey;
            color: white;
        }

        .books-table tr{
            background-color: rgba(0,0,0, 0.5);
        }
    </style>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<video id="video-background" autoplay muted loop>
        <source src="video/tech.mp4" type="video/mp4">
    </video>
    <div class="container">
        <h1 class="text-center" style="color: #f2f2f2;">View Books</h1>
        <div class="search">
            <form method="get">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Search Books" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </div>
            </form>
        </div>
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

        // Search query
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $sql = "SELECT * FROM books WHERE title LIKE '%$search%' OR author LIKE '%$search%'";

        // Execute the query
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Display the books in a table
            echo '<table class="books-table">';
            echo '<tr><th>Title</th><th>Author</th><th>Quantity</th><th>Actions</th></tr>';

            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['title'] . '</td>';
                echo '<td>' . $row['author'] . '</td>';
                echo '<td>' . $row['quantity'] . '</td>';
                echo '<td>';
                echo '<a href="reserve_book.php?id=' . $row['id'] . '" class="btn btn-success" onclick="return confirmReserve()">Reserve</a>';
                echo '</td>';
                echo '</tr>';
            }

            echo '</table>';
        } else {
            echo '<div class="alert alert-info">No books found.</div>';
        }

        // Close the database connection
        $conn->close();
        ?>
    </div>
    <div class="text-center">
        <a href="memberpage.php" class="btn btn-dark mt-2">Return to User Page</a>
    </div>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function confirmReserve() {
            return confirm("Do you wish to reserve this book?");
        }
    </script>
</body>
</html>
