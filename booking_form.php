<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$msg = "";
$room_id = isset($_POST['room_id']) ? $_POST['room_id'] : null;

if (!$room_id) {
    die("Room ID is missing.");
}

// Fetch room details
$room_query = mysqli_query($conn, "SELECT * FROM rooms WHERE id = $room_id");
$room = mysqli_fetch_assoc($room_query);

if (!$room) {
    die("Room not found.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    // Insert booking
    $sql = "INSERT INTO bookings (user_id, room_id, name, email, mobile, from_date, to_date) 
            VALUES ($user_id, $room_id, '$name', '$email', '$mobile', '$from_date', '$to_date')";

    if (mysqli_query($conn, $sql)) {
        // Update room's availability and booked_by (use user_id)
        $updateRoom = "UPDATE rooms SET available = 0, booked_by = $user_id WHERE id = $room_id";
        if (!mysqli_query($conn, $updateRoom)) {
            $msg = "Room booked, but failed to update room info. Error: " . mysqli_error($conn);
        } else {
            $msg = "Room booked successfully!";
        }
    } else {
        $msg = "Error booking room. Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Booking Form - Hostel Reservation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h3>Booking Form for Room <?php echo $room['room_number']; ?></h3>

    <?php if ($msg): ?>
        <div class="alert alert-info"><?php echo $msg; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label>Your Name</label>
            <input type="text" name="name" required class="form-control">
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" required class="form-control">
        </div>
        <div class="mb-3">
            <label>Mobile Number</label>
            <input type="text" name="mobile" required class="form-control">
        </div>
        <div class="mb-3">
            <label>From Date</label>
            <input type="date" name="from_date" required class="form-control">
        </div>
        <div class="mb-3">
            <label>To Date</label>
            <input type="date" name="to_date" required class="form-control">
        </div>
        <input type="hidden" name="room_id" value="<?php echo $room_id; ?>">
        <button type="submit" name="submit" class="btn btn-success">Book Room</button>
    </form>
</body>
</html>
