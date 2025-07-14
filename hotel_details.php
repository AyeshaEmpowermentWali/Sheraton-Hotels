<?php
session_start();
require_once 'db.php';

$hotel_id = isset($_GET['hotel_id']) ? intval($_GET['hotel_id']) : 0;
$stmt = $pdo->prepare("SELECT * FROM hotels WHERE hotel_id = ?");
$stmt->execute([$hotel_id]);
$hotel = $stmt->fetch(PDO::FETCH_ASSOC);

$rooms_stmt = $pdo->prepare("SELECT * FROM rooms WHERE hotel_id = ? AND available = TRUE");
$rooms_stmt->execute([$hotel_id]);
$rooms = $rooms_stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sheraton Hotels - <?php echo $hotel['name']; ?></title>
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
        .hotel-details {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .hotel-details img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 10px;
        }
        .hotel-info {
            padding: 20px;
        }
        .room-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .room-card {
            background: #f9f9f9;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .room-card h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }
        .book-btn {
            display: inline-block;
            padding: 10px 20px;
            background: #1e3c72;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
            transition: background 0.3s;
        }
        .book-btn:hover {
            background: #2a5298;
        }
        @media (max-width: 768px) {
            .room-list {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">Sheraton Hotels</div>
    </header>
    <section class="hotel-details">
        <img src="<?php echo $hotel['image']; ?>" alt="<?php echo $hotel['name']; ?>">
        <div class="hotel-info">
            <h2><?php echo $hotel['name']; ?></h2>
            <p>Location: <?php echo $hotel['location']; ?></p>
            <p>Rating: <?php echo $hotel['rating']; ?></p>
            <p><?php echo $hotel['description']; ?></p>
        </div>
        <div class="room-list">
            <?php
            foreach ($rooms as $room) {
                echo "
                <div class='room-card'>
                    <h3>{$room['room_type']}</h3>
                    <p>Price: \${$room['price']}/night</p>
                    <p>Amenities: {$room['amenities']}</p>
                    <a class='book-btn' onclick='bookRoom({$room['room_id']})'>Book Now</a>
                </div>";
            }
            ?>
        </div>
    </section>
    <script>
        function bookRoom(roomId) {
            const checkIn = prompt('Enter check-in date (YYYY-MM-DD):');
            const checkOut = prompt('Enter check-out date (YYYY-MM-DD):');
            if (checkIn && checkOut) {
                window.location.href = `booking.php?room_id=${roomId}&check_in=${checkIn}&check_out=${checkOut}`;
            }
        }
    </script>
</body>
</html>
