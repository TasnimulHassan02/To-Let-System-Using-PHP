<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start(); 


$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$database = "db"; 

// Create connection
$mysqli = new mysqli($servername, $username, $password, $database);


if (!$mysqli) {
  
    echo "Error connecting to the database. Please try again later.";
    exit;
}


$emailQuery = "SELECT email FROM userinfo ORDER BY id DESC LIMIT 1";


$result = $mysqli->query($emailQuery);

if ($result) {
    $row = $result->fetch_assoc();
    $latestEmail = $row['email'];

    $userDataQuery = "SELECT * FROM user WHERE email = '$latestEmail'";

    $userDataResult = $mysqli->query($userDataQuery);

    if ($userDataResult && $userDataResult->num_rows > 0) {
        $user = $userDataResult->fetch_assoc();


        $name = $user['name'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Details</title>
    <style>
        .search-button {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: Black;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .home-button {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: Black;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body style="background-color: gray; background-size: cover; color: white; font-family: Arial, sans-serif;">
    <div style="max-width: 800px; margin: 50px auto; padding: 20px;">
        <h1 style="text-align: center;"></h1>

        <button class="home-button" onclick="window.location.href='loginhome.html'">Home</button>
        <button class="search-button" onclick="window.location.href='search.php'">Search</button>

        <?php
        
        $conn = new mysqli("localhost", "root", "", "db");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

       
        $sql = "SELECT * FROM addproperty WHERE name != '$name' AND status = 'approved' AND available_status = 'available'";
        $result = $conn->query($sql);

        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div style='margin-top: 30px; background-color: rgba(0, 0, 0, 0.6); padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);'>";
                echo "<h2 style='border-bottom: 2px solid white; padding-bottom: 5px;'>Property Details</h2>";
                echo "<p><strong>Flat ID:</strong> " . $row['Flatid'] . "</p>"; 
                echo "<p><strong>Flat Status:</strong> " . $row['available_status'] . "</p>"; 
                echo "<p><strong>Flat Type:</strong> " . $row['flat_type'] . "</p>"; 
                echo "<p><strong>Location:</strong> " . $row['flat_location'] . "</p>";
                echo "<p><strong>Size:</strong> " . $row['flat_size'] . " sq ft</p>";
                echo "<p><strong>Floor Number:</strong> " . $row['floor_number'] . "</p>"; // Add this line to display floor number

               
                echo "<button onclick=\"showMoreInfo(this)\">Show More Information</button>";
                echo "<div class='moreInfo' style='display: none;'>";
                echo "<p><strong>Bedrooms:</strong> " . $row['bedrooms'] . "</p>";
                echo "<p><strong>Washrooms:</strong> " . $row['washrooms'] . "</p>";
                echo "<p><strong>Balcony:</strong> " . $row['balcony'] . "</p>";
                echo "<p><strong>Kitchen:</strong> " . $row['kitchen'] . "</p>";
                echo "<p><strong>Living Room:</strong> " . $row['living_room'] . "</p>";
                echo "<p><strong>Dining Room:</strong> " . $row['dinning_room'] . "</p>";
                echo "<p><strong>Rent:</strong> " . $row['rent'] . " Taka</p>";
                echo "<p><strong>Owner Phone Number :</strong> " . $row['phone_number'] . "</p>";
                echo "</div>"; 

               
                echo "<a href='view_review_rating.php?Flatid=" . $row['Flatid'] . "'><button>View Review and Ratings</button></a>";
                echo "<a href='add_review_rating.php?Flatid=" . $row['Flatid'] . "'><button>Add Review and Rating</button></a>";

                echo "<a href='booking.php?Flatid=" . $row['Flatid'] . "'><button>Booking</button></a>";
                echo "<a href='reservation.php?Flatid=" . $row['Flatid'] . "'><button>Reservation</button></a>";
                
               
                $photos = explode(",", $row['photos']);
                echo "<div style='margin-top: 20px; display: flex; flex-wrap: wrap;'>";

                
                for ($i = 0; $i < min(count($photos), 2); $i++) {
                    echo "<img src='$photos[$i]' alt='Property Image' style='width: calc(50% - 10px); margin: 5px; border-radius: 5px;'>";
                }

                echo "</div>"; 

                
                if (count($photos) > 2) {
                    echo "<button onclick=\"showMoreImages(this)\">Show More Images</button>";
                    echo "<div class='moreImages' style='display: none;'>";

                   
                    for ($i = 2; $i < count($photos); $i++) {
                        echo "<img src='$photos[$i]' alt='Property Image' style='width: calc(50% - 10px); margin: 5px; border-radius: 5px;'>";
                    }

                    echo "</div>"; 
                }

                echo "</div>"; 
            }
        } else {
            echo "<p>No properties found.</p>";
        }

        $conn->close();
        ?>

        <script>
            function showMoreInfo(button) {
                var moreInfoDiv = button.nextElementSibling;
                if (moreInfoDiv.style.display === 'none') {
                    moreInfoDiv.style.display = 'block';
                    button.textContent = 'Hide Information';
                } else {
                    moreInfoDiv.style.display = 'none';
                    button.textContent = 'Show More Information';
                }
            }

            function showMoreImages(button) {
                var moreImagesDiv = button.nextElementSibling;
                if (moreImagesDiv.style.display === 'none') {
                    moreImagesDiv.style.display = 'block';
                    button.textContent = 'Hide Images';
                } else {
                    moreImagesDiv.style.display = 'none';
                    button.textContent = 'Show More Images';
                }
            }
        </script>
    </div>
</body>
</html>
