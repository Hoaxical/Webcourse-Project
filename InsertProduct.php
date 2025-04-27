<?php
require 'armwareDBconnect.php';

// Fetching form inputs and escaping special characters
$type = mysqli_real_escape_string($conn, $_POST['type']);
$title = mysqli_real_escape_string($conn, $_POST['title']);
$name = mysqli_real_escape_string($conn, $_POST['name']);
$video_link = mysqli_real_escape_string($conn, $_POST['video_link']);
$image1 = mysqli_real_escape_string($conn, $_POST['image1']);
$image2 = mysqli_real_escape_string($conn, $_POST['image2']);
$image3 = mysqli_real_escape_string($conn, $_POST['image3']);
$price = mysqli_real_escape_string($conn, $_POST['price']);

// Specs and Descriptions
$specs = [];
for ($i = 1; $i <= 6; $i++) {
    $specs[] = mysqli_real_escape_string($conn, $_POST['specs_' . $i]);
}

$description_title = mysqli_real_escape_string($conn, $_POST['description_title']);
$descriptions = [];
for ($i = 1; $i <= 7; $i++) {
    $descriptions[] = mysqli_real_escape_string($conn, $_POST['desc_' . $i]);
}

// SQL Query to Insert into `Products` table
$query = "INSERT INTO Products (type, title, name, video_link, image1, image2, image3, price, specs_1, specs_2, specs_3, specs_4, specs_5, specs_6, description_title, desc_1, desc_2, desc_3, desc_4, desc_5, desc_6, desc_7)
          VALUES ('$type', '$title', '$name', '$video_link', '$image1', '$image2', '$image3', '$price', '$specs[0]', '$specs[1]', '$specs[2]', '$specs[3]', '$specs[4]', '$specs[5]', '$description_title', '$descriptions[0]', '$descriptions[1]', '$descriptions[2]', '$descriptions[3]', '$descriptions[4]', '$descriptions[5]', '$descriptions[6]')";

if ($conn->query($query) === TRUE) {
    // Insert the product_id into the relevant type table (GraphicsCard, Motherboard, etc.)
    $product_id = $conn->insert_id;
    $type_table = strtolower($type);  // Determine the type table name (e.g., graphicscard)

    // Insert into the relevant type table
    $insert_type_table_query = "INSERT INTO $type_table (product_id) VALUES ('$product_id')";

    if ($conn->query($insert_type_table_query) === TRUE) {
        echo "Product inserted successfully into Products and $type table";
    } else {
        echo "Error inserting into $type table: " . $conn->error;
    }
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>

?>
