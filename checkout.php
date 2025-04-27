<?php
require 'armwareDBconnect.php'; // Connect to your database

session_start();
$is_logged_in = isset($_SESSION['user_id']); // Check if the user is logged in
if (!$is_logged_in) {
    header('Location: armwareSignIn.html');
    exit;
}

$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

if ($product_id > 0) {
    $query = "SELECT product_id, name, type, price, image2 FROM Products WHERE product_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        die("Product not found.");
    }
} else {
    die("Invalid product ID.");
}

$user_id = $_SESSION["user_id"];
$user_query = "SELECT * FROM user_informations WHERE user_id = ?";
$user_stmt = $conn->prepare($user_query);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user_info = $user_result->fetch_assoc();

// Assigning shipping and billing addresses from user information
$shipping_address = isset($user_info['shipping_address']) ? $user_info['shipping_address'] : ''; 
$billing_address = isset($user_info['billing_address']) ? $user_info['billing_address'] : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $card_provider = htmlspecialchars($_POST['card_provider']);
    $card_number = htmlspecialchars($_POST['card_number']);
    $cvc = htmlspecialchars($_POST['cvc']);
    $expiry_date = htmlspecialchars($_POST['expiry_date']);
    $shipping_address = htmlspecialchars($_POST['shipping_address']);
    $billing_address = htmlspecialchars($_POST['billing_address']);

    $order_date = date('Y-m-d');
    $order_status = 'Pending';

    $insert_order_query = "INSERT INTO Orders (product_id, order_date, order_status) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insert_order_query);
    $stmt->bind_param("iss", $product_id, $order_date, $order_status);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    $insert_user_order_query = "INSERT INTO users_Orders (order_id, user_id) VALUES (?, ?)";
    $stmt = $conn->prepare($insert_user_order_query);
    $stmt->bind_param("ii", $order_id, $user_id);
    $stmt->execute();

    $order_completed = true;
    $order_id_display = $order_id;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | Armware</title>
    <link rel="icon" type="image/png" href="images/ArmwareLogo.png">

    <link rel="stylesheet" href="teststyles.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@100..900&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'League Spartan', sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: none;
            margin-top: 5%;
        }

        .product-info, .user-info {
            width: 45%;
        }

        .product-info h2, .user-info h2 {
            font-family: 'Anton', sans-serif;
            margin-bottom: 10px;
            color: white;
        }

        .product-info p {
            font-size: 1.2rem;
            color: white;
        }

        .product-info img {
            width: 50%;
            margin: 0 auto;
            border-radius: 10px;
        }

        .form-group {
            margin: 10px 0;
        }

        .form-group input {
            width: 50%;
            padding: 10px;
            margin: 5px 0;
            font-size: 1rem;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.44);
            border-radius: 5px;
            color: white;
            background-color: rgb(69, 69, 69);
        }

        .form-group label {
            font-size: 1.2rem;
            color: white;
        }

        .submit-btn {
            margin-top: 5%;
            margin-left: 20%;
            background-color: rgb(69, 69, 69);
            color: white;
            border-radius: 5px;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            transition: 0.3s;
        }

        .submit-btn:hover {
            background-color: rgb(108, 255, 118);
        }

        .divider {
            width: 2px;
            background-color: #333;
            margin: 0 20px;
        }

        #success-message {
            display: none;
            background-color: rgba(78, 249, 255, 0.8);
            padding: 20px;
            margin-top: 20px;
            text-align: center;
            font-size: 1.5rem;
            border-radius: 5px;
        }

        .form-group input::placeholder {
            color: #bbb;
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

<div class="container">
    <div class="product-info">
        <h2>Product Details</h2>
       
        <p><strong>Item:</strong> <?= htmlspecialchars($product['name']); ?></p>
        <p><strong>Item Type:</strong> <?= htmlspecialchars($product['type']); ?></p>
        <p><strong>Price:</strong> $<?= htmlspecialchars($product['price']); ?></p>

    </div>

    <div class="divider"></div>

    <div class="user-info">
        <h2>Purchase Form</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label for="fname">Full Name:</label><br>
                <input type="text" id="fname" name="fname" value="<?= htmlspecialchars($user_info['fname'] . ' ' . $user_info['lname']); ?>" readonly placeholder="Your full name">
            </div>
            <div class="form-group">
                <label for="username">Username:</label><br><br>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($user_info['username']); ?>" readonly placeholder="Your username">
            </div>

            <div class="form-group">
                <label for="card_provider">Card Provider:</label><br>
                <input type="text" id="card_provider" name="card_provider" required placeholder="e.g. Visa, MasterCard">
            </div>
            <div class="form-group">
                <label for="card_number">Card Number:</label><br>
                <input type="text" id="card_number" name="card_number" pattern="\d{16}" required placeholder="16-digit card number">
            </div>
            <div class="form-group">
                <label for="cvc">CVC:</label><br>
                <input type="text" id="cvc" name="cvc" pattern="\d{3}" required placeholder="3-digit CVC">
            </div>
            <div class="form-group">
                <label for="expiry_date">Expiry Date:</label><br>
                <input type="text" id="expiry_date" name="expiry_date" placeholder="MM/YY" pattern="\d{2}/\d{2}" required>
            </div>

            <div class="form-group">
                <label for="shipping_address">Shipping Address:</label><br>
                <input type="text" id="shipping_address" name="shipping_address" value="<?= htmlspecialchars($shipping_address); ?>" readonly>
            </div>

            <div class="form-group">
                <label for="billing_address">Billing Address:</label><br>
                <input type="text" id="billing_address" name="billing_address" value="<?= htmlspecialchars($billing_address); ?>" readonly>
            </div>

            <button type="submit" class="submit-btn">Complete Checkout</button>
        </form>
    </div>
</div>

<?php if (isset($order_completed)): ?>
    <div id="success-message">
        <p>Checkout Completed! Your Order ID is <?= $order_id_display; ?></p>
        <a href="Orders.php">View your Orders</a>
    </div>
    <script>
        setTimeout(function() {
            document.getElementById('success-message').style.display = 'block';
        }, 300);
    </script>
<?php endif; ?>

</body>
</html>
