<?php
session_start();
include('armwareDBconnect.php'); // Corrected db connection

if (!isset($_SESSION['user_id'])) {
    header('Location: armwaresignIn.html');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch Orders for the user
$sql = "SELECT o.order_id, o.total_price, o.order_date, o.order_status, p.name AS product_name, p.image2 AS product_image
        FROM users_Orders uo
        INNER JOIN Orders o ON uo.order_id = o.order_id
        INNER JOIN products p ON o.product_id = p.product_id
        WHERE uo.user_id = ?
        ORDER BY o.order_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders - ArmWare</title>
    <link rel="icon" type="image/png" href="images/ArmwareLogo.png">

    <link rel="stylesheet" href="teststyles.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=League+Spartan:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tomorrow:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bruno+Ace+SC&family=Tomorrow:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'League Spartan', sans-serif;
            margin: 0;
            padding: 0;
        }

        .Orders-container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
        }

        .order-card {
            background: rgb(65, 65, 65) ;
            margin-bottom: 20px;
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            flex-direction: row;
            box-shadow: 0 0 5px rgba(163, 0, 250, 0);
            transition: box-shadow 0.3s ease;
        }

        .order-card:hover {
            box-shadow: 0 0 30px rgb(51, 231, 255);
        }

        .order-image img {
            width: 200px;
            height: 200px;
            object-fit: cover;
        }

        .order-details {
            padding: 20px;
            flex: 1;
        }

        .order-details h3 {
            margin: 0;
            font-size: 24px;
            color: white;
        }

        .order-details p {
            margin: 10px 0;
            font-size: 18px;
            color: white;
        }

        .order-status {
            margin-top: 10px;
            font-weight: bold;
            color: #007bff;
        }

        .no-Orders {
            text-align: center;
            margin-top: 100px;
            font-size: 24px;
            color: #888;
        }
    </style>
</head>

<body>


<header>
    <div class="navbarExterior">
        <a href="index.php">
            <img src="images/ArmwareLogo.png" id="logo" alt="ArmWare logo">
        </a>
        <div class="navbarInterior">
            <ul class="league-spartan">
                <li><a href="Products.html">Products</a></li>
                <li><a href="NearbyRetailers.html">Nearby Retailers</a></li>
                <li><a href="Orders.php">Orders</a></li>
                <li><a href="AboutUs.html">About Us</a></li>
                <div class="logincontainer">
                    <a href="<?php echo $is_logged_in ? 'account.php' : 'armwaresignIn.html'; ?>"><img src="images/loginICON.png" id="loginicon" alt="Login"></a>
                </div>
            </ul>
        </div>
    </div>
</header>

<div class="Orders-container">
    <h1 style="text-align:center; margin-bottom: 40px;">My Orders</h1>

    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="order-card">
                <div class="order-image">
                   
                    <img src="<?php echo htmlspecialchars($row['product_image']); ?>">
                </div>
                <div class="order-details">
                    <h3>Order #<?php echo htmlspecialchars($row['order_id']); ?></h3>
                    <p><strong>Product:</strong> <?php echo htmlspecialchars($row['product_name']); ?></p>
                    <p><strong>Total Price:</strong> $<?php echo htmlspecialchars(number_format($row['total_price'], 2)); ?></p>
                    <p><strong>Order Date:</strong> <?php echo htmlspecialchars(date('F j, Y', strtotime($row['order_date']))); ?></p>
                    <p class="order-status"><strong>Status:</strong> <?php echo htmlspecialchars($row['order_status']); ?></p>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="no-Orders">
            You have no Orders yet.
        </div>
    <?php endif; ?>

</div>

</body>
</html>
