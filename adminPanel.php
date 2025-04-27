<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Product | Armware</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'League Spartan', sans-serif;
        }

        select {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: none;
            background-color: #333;
            color: #fff;
        }

        body {
            background-color: #0e0e0e;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px;
        }

        #logo {
            width: 180px;
            margin-bottom: 30px;
        }

        .form-container {
            background-color: #1c1c1c;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 0 20px rgba(73, 182, 255, 0.15);
            max-width: 700px;
            width: 100%;
        }

        .form-container:nth-of-type(2){
            margin-top: 40px;
        }

        .form-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color:rgb(68, 233, 255);
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: 500;
            margin-bottom: 6px;
        }

        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: none;
            background-color: #333;
            color: #fff;
        }

        textarea {
            resize: vertical;
        }

        .submit-btn {
            margin-top: 20px;
            padding: 12px;
            width: 100%;
            border: none;
            border-radius: 8px;
            background-color:rgb(68, 233, 255);
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }

        .submit-btn:hover {
            background-color: #e63636;
        }
    </style>
</head>
<body>

    <a href="index.php">
        <img src="images/ArmwareLogo.png" id="logo" alt="ArmWare logo">
    </a>

    <div class="form-container">
        <h2>Add New Product</h2>
        <form action="InsertProduct.php" method="POST">
            
            <div class="form-group">
                <label>Type</label>
                <select name="type" required>
                    <option value="">-- Select Type --</option>
                    <option value="GraphicsCard">Graphics Card</option>
                    <option value="Motherboard">Motherboard</option>
                    <option value="Processors">Processors</option>
                    <option value="Gadgets">Gadgets</option>
                </select>
            </div>

            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" required>
            </div>
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" required>
            </div>
            <div class="form-group">
                <label>Video Link</label>
                <input type="text" name="video_link">
            </div>
            
            <div class="form-group">
                <label for="image1">Image 1</label>
                <input type="text" name="image1" id="image1" required>
            </div>

            <div class="form-group">
                <label for="image2">Image 2</label>
                <input type="text" name="image2" id="image2">
            </div>

            <div class="form-group">
                <label for="image3">Image 3</label>
                <input type="text" name="image3" id="image3">
            </div>

            <div class="form-group">
                <label>Price</label>
                <input type="number" step="0.01" name="price" required>
            </div>
            <?php
            for ($i = 1; $i <= 6; $i++) {
                echo '<div class="form-group"><label>Specs ' . $i . '</label><input type="text" name="specs_' . $i . '"></div>';
            }
            ?>
            <div class="form-group">
                <label>Description Title</label>
                <input type="text" name="description_title" required>
            </div>
            <?php
            for ($i = 1; $i <= 7; $i++) {
                echo '<div class="form-group"><label>Description ' . $i . '</label><textarea name="desc_' . $i . '" rows="2"></textarea></div>';
            }
            ?>
            <button type="submit" class="submit-btn">Insert Product</button>
        </form>
    </div>

    <div class="form-container">
        <h2>Remove Product</h2>
        <form action="deleteProducts.php" method="POST">
            <div class="form-group">
                <label for="product_id">Product ID</label>
                <input type="number" name="product_id" id="product_id" required>
            </div>

            <button type="submit" class="submit-btn">Remove Product</button>
        </form>
    </div>

</body>
</html>
