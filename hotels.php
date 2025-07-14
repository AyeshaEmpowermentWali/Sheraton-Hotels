<?php
session_start();
require_once 'db.php';

$location = isset($_GET['location']) ? $_GET['location'] : '';
$check_in = isset($_GET['check_in']) ? $_GET['check_in'] : '';
$check_out = isset($_GET['check_out']) ? $_GET['check_out'] : '';
$rating = isset($_GET['rating']) ? $_GET['rating'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';
$amenities = isset($_GET['amenities']) ? $_GET['amenities'] : '';

$query = "SELECT h.*, r.room_id, r.room_type, r.price, r.amenities 
          FROM hotels h 
          JOIN rooms r ON h.hotel_id = r.hotel_id 
          WHERE r.available = TRUE";
$params = [];

if ($location) {
    $query .= " AND h.location LIKE ?";
    $params[] = "%$location%";
}
if ($rating) {
    $query .= " AND h.rating >= ?";
    $params[] = $rating;
}
if ($amenities) {
    $query .= " AND r.amenities LIKE ?";
    $params[] = "%$amenities%";
}
if ($sort == 'low-high') {
    $query .= " ORDER BY r.price ASC";
} elseif ($sort == 'high-low') {
    $query .= " ORDER BY r.price DESC";
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sheraton Hotels - Listings</title>
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
        .hotel-list {
            max-width: 1200px;
            margin: 20px auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .hotel-card {
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            transition: transform 0.3s;
        }
        .hotel-card:hover {
            transform: scale(1.05);
        }
        .hotel-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .hotel-info {
            padding: 15px;
        }
        .hotel-info h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }
        .hotel-info p {
            font-size: 14px;
            color: #666;
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
            .hotel-list {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">Sheraton Hotels</div>
    </header>
    <section class="hotel-list">
        <?php
        foreach ($results as $result) {
            echo "
            <div class='hotel-card'>
                <img src='{$result['image']}' alt='{$result['name']}'>
                <div class='hotel-info'>
                    <h3>{$result['name']} - {$result['room_type']}</h3>
                    <p>Location: {$result['location']}</p>
                    <p>Rating: {$result['rating']}</p>
                    <p>Price: \${$result['price']}/night</p>
                    <p>Amenities: {$result['amenities']}</p>
                    <a class='book-btn' onclick='bookRoom({$result['room_id']}, \"{$check_in}\", \"{$check_out}\")'>Book Now</a>
                </div>
            </div>";
        }
        ?>
    </section>
    <script>
        function bookRoom(roomId, checkIn, checkOut) {
            window.location.href = `booking.php?room_id=${roomId}&check_in=${checkIn}&check_out=${checkOut}`;
        }
    </script>
</body>
</html>
