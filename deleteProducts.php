<?php
require 'armwareDBconnect.php';

// Get product_id from form
$product_id = mysqli_real_escape_string($conn, $_POST['product_id']);

// Query to get the type of the product from the Products table
$query = "SELECT type FROM Products WHERE product_id = '$product_id'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $type = $row['type'];
    $type_table = strtolower($type);  // Table name based on product type

    // Delete from respective type table
    $delete_type_table_query = "DELETE FROM $type_table WHERE product_id = '$product_id'";

    if ($conn->query($delete_type_table_query) === TRUE) {
        // After deleting from the type table, delete from the Products table
        $delete_product_query = "DELETE FROM Products WHERE product_id = '$product_id'";

        if ($conn->query($delete_product_query) === TRUE) {
            echo "Product deleted successfully from Products and $type table";
        } else {
            echo "Error deleting from Products table: " . $conn->error;
        }
    } else {
        echo "Error deleting from $type table: " . $conn->error;
    }
} else {
    echo "Product not found.";
}

$conn->close();
?>
