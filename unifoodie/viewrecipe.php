<?php
$username = $_COOKIE['username'];
?>



<!DOCTYPE HTML>
<head>
  <script src="deletecookies.js"></script>
  <link rel="stylesheet" href="./CSS/Header.css">
  <link rel="stylesheet" href="./CSS/Navbar.css">
  <link rel="stylesheet" href="./CSS/Background.css">
  <link rel="stylesheet" href="./CSS/Menu.css">
  <link rel="stylesheet" href="./CSS/register.css">
    <link rel="stylesheet" href="./CSS/recipes.css">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@200&display=swap" rel="stylesheet">
</head>


<body>
  <div class="header">
      <h1>UniFoodie</h1>
      <a href="homepage.php"><img style="float: left;" src=Logo.png></a>
      <a href="index.php"><button class = "LogOutButton" style="float: right;" onclick="deleteAllCookies()">Log Out</button><a/>
    <button class = "LogOutButton" style="float: right; width: 100px;"><?php echo "Welcome " . $username . "!";?></button>
  </div>

  <div class="navbar">
    <a href="homepage.php"><button class = "navBarButtons" style="float: left;">Home</button></a>
    <a href="menu.php"><button class = "navBarButtons" style="float: left;">Menu</button></a>
    <a href="about.php"><button class = "navBarButtons" style="float: left;">About</button></a>
    <a href="myrecipes.php"><button class = "navBarButtons" style="float: right;">My Recipes</button></a>
	<a href="favourites.php"><button class = "navBarButtons" style="float: right;">My Favourites</button></a>
   </div>

  <div class="background">
<?php
    include_once 'webpages/dbinfo.php';

	$id = $_COOKIE['_id'];
	$sql = "SELECT * FROM recipe
			WHERE Recipe_ID = '$id';";
	$result = mysqli_query($conn, $sql);
	$verifyResult = mysqli_num_rows($result);
	if ($verifyResult > 0) {
		while ($row = mysqli_fetch_assoc($result)) {
      echo '<img class="picture" src=uploads/'.$row['Image_Link']. '.jpg' . ' ></img>';
      echo '<div class="description">';
			echo '<h1>Ingredients</h1>';
			$ingredients = $row['Ingredients'];
			$output = str_replace(array('.'),'<br />', $ingredients);
			echo '<p>' . $output . '</p>';
			echo '<h1>Method</h1>';
			$method = $row['Method'];
			$output2 = str_replace(array('.'),'<br />', $method);
			echo '<p>' . $output2 . '</p>';
			echo'<form action="writereview.php" method="post">';
			echo'<button class="btn" type="submit" name="submit">Add Review</button>';
      echo '<br><br>';
			echo'</form>';
			echo'<form action="" method="post">';
			echo'<button class="btn" style="float: left;" type="submit" name="submitb">Favourite</button>';
			echo'</form>';
        }

      echo '<div class="recipeContainer">';
      echo '<br><h1>Reviews</h1>';
      $sql = "SELECT Review_Poster, Recipe_Comment, Recipe_Rating FROM recipe_review
          WHERE Recipe_ID = '$id';";
      $result = mysqli_query($conn, $sql);
      $verifyResult = mysqli_num_rows($result);
    if ($verifyResult > 0) {
      while ($row = mysqli_fetch_assoc($result))
    {
      foreach ($row as $values)
      {
        echo $values;
        echo '<br>';
      }
      echo '<br>';
    }
  }
}
    echo '</div>';

    if (isset($_POST['submitb']))
    {
      $username = $_COOKIE['username'];

        $sql = "insert into user_favourites(username, Recipe_ID)
		  values('$username', '$id')";
        if ($conn->query($sql) === TRUE) {
          echo "New record created successfully";
        }
	    else {
          echo "Error: already in favourites.";
        }
    }
?>
</div></div>



</body>
