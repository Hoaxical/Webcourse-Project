<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Armware</title>

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


#editinfo{
    border: 5px solid rgb(132, 131, 131);
    background-color: rgba(99, 99, 99, 0.62);
    color: white;
    border-radius: 25px;
    height: 65px;
    width: 10%;
    margin-left: 35%;
    font-size: 100%;
}

#editinfo:hover{
    transition: 0.2s ease-in-out;
    border: 0px solid rgb(0, 0, 0);
    background-color: rgb(104, 197, 255);
    color: black;
    box-shadow: 0 0 10px rgba(104, 197, 255, 0.8);
}


#updateinfo{
    border: 5px solid rgb(132, 131, 131);
    background-color: rgba(99, 99, 99, 0.62);
    color: white;
    border-radius: 25px;
    height: 65px;
    width: 10%;
    margin-left: 10%;
    font-size: 100%;
}


#updateinfo:hover{
    transition: 0.2s ease-in-out;
    border: 0px solid rgb(0, 0, 0);
    background-color: rgb(137, 252, 105);
    color: black;
    box-shadow: 0 0 10px rgba(137, 252, 105, 0.8);

}

    
#logout_button{
    border-radius: 5px;
    background-color: rgba(255, 29, 29, 0);    
    height: auto;
    width: 80%;
    margin-left: 10%;
    margin-top: 5%;


}


#logout_id{
    border: 5px solid rgb(132, 131, 131);
    background-color: rgba(99, 99, 99, 0.62);
    color: white;
    border-radius: 25px;
    height:65px;
    width: 10%;
    margin-left: 45%;
    font-size: 100%;
}


#logout_id:hover{
    transition: 0.2s ease-in-out;
    border: 0px;
    background-color: rgb(255, 72, 72);
    color: black;
    box-shadow: 0 0 10px rgba(255, 72, 72, 0.8);
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
                            <a href="armwaresignIn.html"><img src="images/loginICON.png" id="loginicon" alt="Login"></a>
                            
                        </div>

                    </ul>

                </div>
        
    </div>

</header>

<div id="account_information">

    <form name="accountInfoFORM" action="account.php" method="POST">

    <?php

        require 'armwareDBconnect.php';
        session_start();

        if (!isset($_SESSION["user_id"])) {
            header("Location: armwaresignin.html");
            exit;
        }

        if (isset($_POST['logout'])) {
            session_unset();
            session_destroy();
            header("Location: index.php");
            exit;
        }

        $userid = $_SESSION['user_id'];

        // If the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["updateinfo"])) {
            $email = $_POST["email"];
            $billing_address = $_POST["billingaddress"];
            $shipping_address = $_POST["shippingaddress"];

            // Debug output to check if data is being received
            echo "Last Change Requests: <br>";
            echo "Email: $email <br>";
            echo "Billing Address: $billing_address <br>";
            echo "Shipping Address: $shipping_address <br>";


            $update_userinfoQuery = "
                UPDATE user_informations SET
                email = ?,
                billing_address = ?,
                shipping_address = ?
                WHERE user_id = ?;
            ";

            $prep_stmt = $conn->prepare($update_userinfoQuery);
            $prep_stmt->bind_param("sssi", $email, $billing_address, $shipping_address, $userid);
            $prep_stmt->execute();
            $prep_stmt->close();
        }

        // Always fetch latest data after update or page load
        $getuserinfoQuery = "
            SELECT fname, lname, username, email, billing_address, shipping_address
            FROM user_informations
            WHERE user_id = ?;
        ";

        $prep_stmt = $conn->prepare($getuserinfoQuery);
        $prep_stmt->bind_param("i", $userid);
        $prep_stmt->execute();
        $prep_stmt->bind_result($fname, $lname, $username, $email, $billing, $shipping);

        $prep_stmt->fetch();
        $prep_stmt->close();

        $conn->close();

    ?>

    <table id="AccountInformationsTable">

    <th colspan="2" style="height: 50px; font-size: 30px; text-align: center;" class="league-spartan">Account Information</th>

    <tr>

    <td width="40%" class="tomorrow-regular">First Name:
        <input type="text" name="firstName" id="firstName" value="<?php echo htmlspecialchars($fname); ?>" disabled><br>
        Last Name:
        <input type="text" name="lastName" id="lastName" value="<?php echo htmlspecialchars($lname); ?>" disabled><br>
        Username:
        <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($username); ?>" disabled><br>
    </td>
    <td width="40%" class="tomorrow-regular"><p style="margin-bottom: 0; font-size:25px; margin-top: 0;">Contact</p><br>

    Email address: <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" readonly>

    </td>

    </tr>

    
    <th colspan="2" class="league-spartan" style="height: 50px; font-size: 30px; text-align: center;">Address Book</th>

    <tr>

    <td width="40%" class="tomorrow-regular"><p style="margin-bottom: 0; font-size:25px; margin-top: 0;">Billing Address</p><br>
    <textarea name="billingaddress" id="billingaddressBOXid" class="tomorrow-regular" cols="40" rows="6" readonly><?php echo htmlspecialchars($billing); ?></textarea>
    </td>
    <td width="40%" class="tomorrow-regular"><p style="margin-bottom: 0; font-size:25px; margin-top: 0;">Shipping Address</p><br>
    <textarea name="shippingaddress" id="shippingaddressBOXid" class="tomorrow-regular" cols="40" rows="6" readonly><?php echo htmlspecialchars($shipping); ?></textarea>
    </td>

    </tr>

    </table>

    <div id="accountsreview_button">

        <input type="button" value="Edit Information" id="editinfo" class="league-spartan">
        <input type="submit" name="updateinfo" value="Update Information" id="updateinfo" class="league-spartan">
    </div>

        
    <div id="logout_button">

        <input type="submit" name="logout" value="Logout" id="logout_id" class="league-spartan">
    </div>

    </form>

</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

    console.log("jquery is working!");

    $(document).ready(function() {
        $("#editinfo").click(function() {
            // Enable input fields
            $("#email, #billingaddressBOXid, #shippingaddressBOXid").prop("readonly", false);
        });

        $("form").submit(function() {
            alert("Form is being submitted");
        });

    });

</script>

   
</body>


</html>