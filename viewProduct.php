<?php
require 'armwareDBconnect.php'; // Connect to your database

// Get product_id from URL
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

if ($product_id > 0) {
    // Fetch product details from database
    $query = "SELECT * FROM Products WHERE product_id = ?";
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

$conn->close(); // Close database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['title']); ?> | Armware</title>
    <link rel="stylesheet" href="teststyles.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=League+Spartan:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tomorrow:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bruno+Ace+SC&family=Tomorrow:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">


    <style>

        #part-name{
            position: absolute;
            margin-top: -20%;
            right: 35%;
            font-size: 30px;
        }

                
        .buttons input[value="Checkout"]{
            height:50px;
            width: 110px;
            border-radius: 10px;
            border: none;
            color: white;
            background-color: rgb(69, 69, 69);
            transition: 0.25s all ease-in-out;
            font-size: 20px;
        }


        .buttons input[value="Checkout"]:hover{
            color: black;
            background-color: rgba(78, 249, 255);
            box-shadow: 0 0 20px rgba(78, 249, 255, 0.49);
        }

    </style>


</head>
<body>

<header>
    <div class="navbarExterior">
        <a href="index.php"><img src="images/ArmwareLogo.png" id="logo" alt="ArmWare logo"></a>
        <div class="navbarInterior">
            <ul class="league-spartan">
                <li><a href="Products.html">Products</a></li>
                <li><a href="NearbyRetailers.html">Nearby Retailers</a></li>
                <li><a href="Orders.php">Orders</a></li>
                <li><a href="AboutUs.html">About Us</a></li>
                <div class="logincontainer">
                    <a href="armwareAccountSetup.html"><img src="images/loginICON.png" id="loginicon" alt="Login"></a>
                </div>
            </ul>
        </div>
    </div>
</header>

<h1 class="bebas-neue-regular"><?= htmlspecialchars($product['title']); ?></h1>

<div class="part-video-container">
    <?php if (!empty($product['video_link'])): ?>
        <video src="<?= htmlspecialchars($product['video_link']); ?>" id="video" alt="Product video"></video>
        <div class="controls">
            <input type="range" id="seek-bar" value="0">
        </div>
        <p class="anton-regular">360° view</p>
    <?php endif; ?>
</div>

<h2 id="part-name" class="anton-regular"><?= htmlspecialchars($product['name']); ?></h2>

<div class="part-button">
    <div class="buttons">
        <a href="checkout.php?product_id=<?= $product_id; ?>">
            <input type="button" value="Checkout" class="league-spartan">
        </a>
    </div>
</div>

<div class="part-description">
    <h3 class="anton-regular" style="color: white; font-size: 1.5rem;">Part Specification</h3>
    <ul class="tomorrow-regular">
        <?php for ($i = 1; $i <= 6; $i++): ?>
            <?php if (!empty($product["specs_$i"])): ?>
                <li><?= htmlspecialchars($product["specs_$i"]); ?></li>
            <?php endif; ?>
        <?php endfor; ?>
    </ul>
</div>

<h4 style="font-size: 3rem; margin-top: 150px; font-style: italic; text-align: center;" class="league-spartan">
    <?= htmlspecialchars($product['description_title']); ?>
</h4>

<div class="part-txt">
    <?php for ($i = 1; $i <= 7; $i++): ?>
        <?php if (!empty($product["desc_$i"])): ?>
            <p style="text-align: justify; font-size: 1.3rem;" class="tomorrow-regular">
                <?= htmlspecialchars($product["desc_$i"]); ?>
            </p>
        <?php endif; ?>
    <?php endfor; ?>
</div>

<?php for ($i = 1; $i <= 3; $i++): ?>
    <?php if (!empty($product["image$i"])): ?>
        <img src="<?= htmlspecialchars($product["image$i"]); ?>" id="part-display" alt="Product Image <?= $i; ?>" style="display: block; margin: auto;">
    <?php endif; ?>
<?php endfor; ?>

<footer>
    <div class="footerheader">
        <div class="footerbar">
            <ul>
                <li>Quick Links</li>
                <li><a href="index.php">Homepage</a></li>
                <li><a href="Products.html">Products</a></li>
                <li><a href="NearbyRetailers.html">Nearby Retailers</a></li>
                <li><a href="Orders.php">Orders</a></li>
                <li><a href="AboutUs.html">About Us</a></li>
            </ul>
            <ul>
                <li>Socials</li>
                <li><a href="https://x.com/home">X (Twitter)</a></li>
                <li><a href="https://www.youtube.com/">YouTube</a></li>
            </ul>
        </div>
    </div>
</footer>


<!---------------------- JQUERY -------------------------------->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
$(document).ready(function() {
    const video = $('#video');
    const seekBar = $('#seek-bar');

    if (video.length) {
        video.on('timeupdate', function() {
            seekBar.val((100 / video[0].duration) * video[0].currentTime);
        });

        seekBar.on('input', function() {
            video[0].currentTime = (seekBar.val() / 100) * video[0].duration;
        });
    }
});



</script>

</body>
</html>
