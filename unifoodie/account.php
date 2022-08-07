<?php
    require "dbinfo.php"
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="./CSS/Header.css">
        <link rel="stylesheet" href="./CSS/Navbar.css">
        <link rel="stylesheet" href="./CSS/Background.css">
        <link rel="stylesheet" href="./CSS/register.css">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@200&display=swap" rel="stylesheet">
        <title>Register Output</title>
    </head>

    <body>
      <div class="header">
          <h1>UniFoodie</h1>
          <a href="index.php"><img style="float: left;" src=Logo.png></a>
          <a href="login.php"><button class = "LogOutButton" style="float: right;">Login</button><a/>
    </div>

    <div class="navbar">
          <a href="index.php"><button class = "navBarButtons" style="float: left;">Home</button></a>
          <a href="aboutloggedout.php"><button class = "navBarButtons" style="float: left;">About</button></a>
    </div>
      <div class = "register-box">
          <h1>Register</h1>
      <form method='POST' action='account.php'>
        <div class = "textbox">
  		  <input type="text" placeholder="Username" name="username">
        </div>
  			<div class = "textbox">
  			<input type="text" placeholder="First name" name="firstname">
        </div>
  			<div class = "textbox">
  			<input type="text" placeholder="Surname" name="lastname">
        </div>
  			<div class = "textbox">
        <input type="text" placeholder="Phone number" name="mobile">
        </div>
  			<div class = "textbox">
  			<input type="date" name="dob">
        </div>
  			<div class = "textbox">
  			<input type="password" placeholder="Password" name="password">
        </div>
  			<div class = "textbox">
        <input type="password" placeholder="Confirm password" name="confirmpassword">
        </div>
          <p><input type="submit" class="btn" VALUE="Submit Registration Information"></p>
        </div>

<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$username = $_POST["username"];
	$firstname = $_POST["firstname"];
	$lastname = $_POST["lastname"];
	$mobile = $_POST["mobile"];
	$dob = $_POST["dob"];
	$password = $_POST["password"];
	$confirmpassword = $_POST["confirmpassword"];
	$usererr = "";
	$usererrlen = "";
	$pwerr = "";
	$pwerrlen = "";
	$dberr = "";
	$firsterrlen = "";
	$lasterrlen = "";

	if($username == "")
	{
		$usererrlen = "Username cannot be empty";
	}
  if(strlen($username) <= 5 or strlen($username) >= 20)
  {
    $usererrlen = ("Username must be between 5 and 20 characters." . strlen($username));
  }

	if($firstname == "")
	{
		$firsterrlen = "First name cannot be empty";
	}

	if($lastname == "")
	{
		$lasterrlen = "Last name cannot be empty";
	}

	if($password != $confirmpassword)
	{
		$pwerr = "Passwords do not match";
	}

	if(strlen($password) < 6)
	{
		$pwerrlen = "Password should be at least 6 characters";
	}

	$connection = new mysqli($dbserver, $dbusername, $dbpassword, $dbdatabase);
  if ($connection->connect_error)
  {
      // Unable to make a connection.  Display the error message returned
      $dberr = "Unable to establish a connection to the database $connection->connect_error";
  }

	else
	{

		$stmt = $connection->prepare("select username from User_Profile where User_Profile.username = ?");
		$stmt->bind_param("s",$username);
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result -> fetch_assoc();
        if (!empty($row))
		{
			$usererr = "Username Already exists";
		}
		$stmt->close();

    $query = "select username from User_Profile where User_Profile.username = ?";
        $statement = $connection->prepare($query);
    $statement->bind_param('s', $username);
    $statement->execute();
        $statement->store_result();
        $statement->bind_result($usercheck);
    if (is_null($usercheck))
    {
    	$usererr = "";
    }
    else
    {
    	$usererr = "Username Already exists";
    }
    $statement->close();

        $connection->close();
	}

  if($usererr != "" || $pwerr != "" || $pwerrlen != "" || $dberr != "" || $usererrlen != "" || $firsterrlen != "" || $lasterrlen != "")
  {
    redisplay($usererr,$pwerr,$pwerrlen,$dberr,$usererrlen,$firsterrlen,$lasterrlen);
  }
  else
  {
    $connection = new mysqli($dbserver, $dbusername, $dbpassword, $dbdatabase);
    if ($connection->connect_error)
    {
      // Unable to make a connection.  Display the error message returned
      $dberr = "Unable to establish a connection to the database $connection->connect_error";
    }
    else
    {
      $query = "INSERT INTO User_Profile (username, Forename, Surname, password, dob, mobile) VALUES (?, ?, ?, ?, ?, ?)";
      $statement = $connection->prepare($query);
      $statement->bind_param('ssssss', $username, $firstname, $lastname, $password, $dob, $mobile);
      $statement->execute();
      $statement->close();
      $connection->close();
      echo("<p>User added.</p>");
    }
  }
}

function redisplay($usererr,$pwerr,$pwerrlen,$dberr,$usererrlen,$firsterrlen,$lasterrlen)
	{
		if($usererr != "" )
		{
			echo "<p>$usererr</p>";
		}
		if($pwerr != "" )
		{
			echo "<p>$pwerr</p>";
		}
		if($pwerrlen != "" )
		{
			echo "<p>$pwerrlen</p>";
		}
		if($dberr != "" )
		{
			echo "<p>$dberr</p>";
		}
		if($usererrlen != "" )
		{
			echo "<p>$usererrlen</p>";
		}
		if($firsterrlen != "" )
		{
			echo "<p>$firsterrlen</p>";
		}
		if($lasterrlen != "" )
		{
			echo "<p>$lasterrlen</p>";
		}
	}
?>



</body>
</html>
