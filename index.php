<?php
session_start();
require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sheraton Hotels - Home</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        body {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: #fff;
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
        .search-container {
            text-align: center;
            padding: 50px 20px;
            background: url('https://images.unsplash.com/photo-1566073771259-6a8506099945') no-repeat center/cover;
            color: #fff;
        }
        .search-container h1 {
            font-size: 36px;
            margin-bottom: 20px;
        }
        .search-box {
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .search-box input, .search-box select, .search-box button {
            padding: 10px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
        }
        .search-box input {
            width: 200px;
        }
        .search-box button {
            background: #1e3c72;
            color: #fff;
            cursor: pointer;
            transition: background 0.3s;
        }
        .search-box button:hover {
            background: #2a5298;
        }
        .featured-hotels {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .hotel-grid {
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
            color: #333;
        }
        .hotel-info h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }
        .hotel-info p {
            font-size: 14px;
            color: #666;
        }
        .filters {
            margin: 20px 0;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .filters select {
            padding: 10px;
            border-radius: 5px;
        }
        @media (max-width: 768px) {
            .search-box input {
                width: 100%;
            }
            .hotel-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">Sheraton Hotels</div>
    </header>
    <div class="search-container">
        <h1>Find Your Perfect Stay</h1>
        <div class="search-box">
            <input type="text" id="location" placeholder="Destination">
            <input type="date" id="check-in">
            <input type="date" id="check-out">
            <select id="rating">
                <option value="">Select Rating</option>
                <option value="4">4+ Stars</option>
                <option value="3">3+ Stars</option>
            </select>
            <button onclick="searchHotels()">Search</button>
        </div>
        <div class="filters">
            <select id="price-sort">
                <option value="">Sort by Price</option>
                <option value="low-high">Low to High</option>
                <option value="high-low">High to Low</option>
            </select>
            <select id="amenities">
                <option value="">Select Amenities</option>
                <option value="Wi-Fi">Wi-Fi</option>
                <option value="Pool">Pool</option>
                <option value="Spa">Spa</option>
            </select>
        </div>
    </div>
    <section class="featured-hotels">
        <h2>Featured Hotels</h2>
        <div class="hotel-grid">
            <?php
            $stmt = $pdo->query("SELECT * FROM hotels");
            while ($hotel = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "
                <div class='hotel-card' onclick='viewHotel({$hotel['hotel_id']})'>
                    <img src='{$hotel['image']}' alt='{$hotel['name']}'>
                    <div class='hotel-info'>
                        <h3>{$hotel['name']}</h3>
                        <p>{$hotel['location']} | Rating: {$hotel['rating']}</p>
                        <p>{$hotel['description']}</p>
                    </div>
                </div>";
            }
            ?>
        </div>
    </section>
    <script>
        function searchHotels() {
            const location = document.getElementById('location').value;
            const checkIn = document.getElementById('check-in').value;
            const checkOut = document.getElementById('check-out').value;
            const rating = document.getElementById('rating').value;
            const sort = document.getElementById('price-sort').value;
            const amenities = document.getElementById('amenities').value;
            window.location.href = `hotels.php?location=${location}&check_in=${checkIn}&check_out=${checkOut}&rating=${rating}&sort=${sort}&amenities=${amenities}`;
        }
        function viewHotel(hotelId) {
            window.location.href = `hotel_details.php?hotel_id=${hotelId}`;
        }
    </script>
</body>
</html>
