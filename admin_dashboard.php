<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

// Fetch stats
$total_rooms = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM rooms"));
$available_rooms = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM rooms WHERE available = 1"));
$booked_rooms = $total_rooms - $available_rooms;

// Fetch all bookings
$bookings = mysqli_query($conn, "
    SELECT r.room_number, b.name, b.email, b.mobile, b.from_date, b.to_date 
    FROM bookings b 
    JOIN rooms r ON b.room_id = r.id 
    ORDER BY b.from_date DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h3>Admin Dashboard 🛠️</h3>
    <a href="logout_admin.php" class="btn btn-danger btn-sm float-end">Logout</a>
    <hr>

    <div class="row text-center">
        <div class="col-md-4">
            <div class="bg-primary text-white p-3 rounded">
                <h4>Total Rooms</h4>
                <h2><?php echo $total_rooms; ?></h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="bg-success text-white p-3 rounded">
                <h4>Available Rooms</h4>
                <h2><?php echo $available_rooms; ?></h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="bg-danger text-white p-3 rounded">
                <h4>Booked Rooms</h4>
                <h2><?php echo $booked_rooms; ?></h2>
            </div>
        </div>
    </div>

    <hr>
    <h4>Booking Details</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Room Number</th>
                <th>Name</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>From Date</th>
                <th>To Date</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($booking = mysqli_fetch_assoc($bookings)): ?>
                <tr>
                    <td><?php echo $booking['room_number']; ?></td>
                    <td><?php echo $booking['name']; ?></td>
                    <td><?php echo $booking['email']; ?></td>
                    <td><?php echo $booking['mobile']; ?></td>
                    <td><?php echo $booking['from_date']; ?></td>
                    <td><?php echo $booking['to_date']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
