<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$name = $_SESSION['name'];
$user_id = $_SESSION['user_id'];

// Fetch all rooms with user name of booked_by (JOIN)
$rooms = mysqli_query($conn, "
    SELECT rooms.*, users.name AS booked_by_name 
    FROM rooms 
    LEFT JOIN users ON rooms.booked_by = users.id 
    ORDER BY room_number ASC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Hostel Reservation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h3>Welcome, <?php echo htmlspecialchars($name); ?> 👋</h3>
    <a href="logout.php" class="btn btn-danger btn-sm float-end">Logout</a>
    <hr>
    <h4>Available Rooms</h4>
    <h3>For Boys from 101-125</h3>
    <h3>For Girls from 126-150</h3>

    <div class="row">
        <?php while ($room = mysqli_fetch_assoc($rooms)): ?>
            <?php
                $room_id = $room['id'];
                $room_number = $room['room_number'];
                $booked_by_name = $room['booked_by_name'];
                $booked = !$room['available'];
            ?>
            <div class="col-md-2 mb-3">
                <form method="POST" action="booking_form.php">
                    <input type="hidden" name="room_id" value="<?php echo $room_id; ?>">
                    <button class="btn btn-<?php echo $booked ? 'secondary' : 'primary'; ?> w-100" 
                            <?php echo $booked ? 'disabled' : ''; ?>>
                        Room <?php echo $room_number; ?>
                        <?php if ($booked): ?>
                            <br><small>Booked by <?php echo htmlspecialchars($booked_by_name); ?></small>
                        <?php endif; ?>
                    </button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
