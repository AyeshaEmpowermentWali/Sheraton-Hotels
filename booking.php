<?php
session_start();
require_once 'db.php';

$room_id = isset($_GET['room_id']) ? intval($_GET['room_id']) : 0;
$check_in = isset($_GET['check_in']) ? $_GET['check_in'] : '';
$check_out = isset($_GET['check_out']) ? $_GET['check_out'] : '';
$user_id = 'guest_' . uniqid(); // For simplicity, using a generated guest ID

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("SELECT price FROM rooms WHERE room_id = ?");
    $stmt->execute([$room_id]);
    $room = $stmt->fetch(PDO::FETCH_ASSOC);
    $price = $room['price'];
    
    $check_in_date = new DateTime($check_in);
    $check_out_date = new DateTime($check_out);
    $days = $check_in_date->diff($check_out_date)->days;
    $total_price = $price * $days;

    $stmt = $pdo->prepare("INSERT INTO bookings (user_id, room_id, check_in, check_out, total_price) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $room_id, $check_in, $check_out, $total_price]);
    
    $stmt = $pdo->prepare("UPDATE rooms SET available = FALSE WHERE room_id = ?");
    $stmt->execute([$room_id]);
    
    $confirmation = "Booking confirmed! Total: $$total_price for $days nights.";
}

$stmt = $pdo->prepare("SELECT h.name, r.room_type, r.price, r.amenities 
                       FROM rooms r 
                       JOIN hotels h ON r.hotel_id = h.hotel_id 
                       WHERE r.room_id = ?");
$stmt->execute([$room_id]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sheraton Hotels - Booking</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        body {
            background: #f4f4f4;
            color: #333;
        }
        header {
            background: #fff;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #1e3c72;
        }
        .booking-form {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .booking-form h2 {
            margin-bottom: 20px;
        }
        .booking-form p {
            font-size: 16px;
            margin-bottom: 10px;
        }
        .booking-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .booking-form button {
            padding: 10px 20px;
            background: #1e3c72;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .booking-form button:hover {
            background: #2a5298;
        }
        .confirmation {
            color: green;
            font-weight: bold;
            margin-top: 20px;
        }
        @media (max-width: 768px) {
            .booking-form {
                margin: 20px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">Sheraton Hotels</div>
    </header>
    <section class="booking-form">
        <h2>Book Your Stay</h2>
        <p>Hotel: <?php echo $room['name']; ?></p>
        <p>Room Type: <?php echo $room['room_type']; ?></p>
        <p>Price: $<?php echo $room['price']; ?>/night</p>
        <p>Amenities: <?php echo $room['amenities']; ?></p>
        <p>Check-in: <?php echo $check_in; ?></p>
        <p>Check-out: <?php echo $check_out; ?></p>
        <form method="POST">
            <input type="hidden" name="room_id" value="<?php echo $room_id; ?>">
            <button type="submit">Confirm Booking</button>
        </form>
        <?php if (isset($confirmation)) { ?>
            <p class="confirmation"><?php echo $confirmation; ?></p>
            <button onclick="window.location.href='index.php'">Back to Home</button>
        <?php } ?>
    </section>
</body>
</html>
