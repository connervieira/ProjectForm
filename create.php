<?php

// This uses the V0LT authentication system, and will need to be replaced
session_start();
if (isset($_SESSION['loggedin'])) {
	$username = $_SESSION['username'];
} else {
	header("Location: /login.php");
	exit();
}

$whitelist = Array("cvieira", "testing"); // This is a whitelist of which users can make posts.

$admin = "cvieira"; // This is the username of the admin. This user will get special privileges.
$folderName = "cntprojects"; // This is the name of the parent folder of the projects system.


// Load the projects database 
$projectsArray = unserialize(file_get_contents('/Library/Server/Web/Data/Sites/Default/' . $folderName . '/projectsArray.txt'));
$title = $_POST['title'];
$description = $_POST['description'];

if (strlen($title) >= 500) {
	echo "Project title must be less than 500 characters";
	echo "<br><a href='/" . $folderName . "'>Back to Main Page</a>";
	exit();
}
if (strlen($description) >= 10000) {
	echo "Community name must be less than 10000 characters";
	echo "<br><a href='/" . $folderName . "'>Back to Main Page</a>";
	exit();
}

//Security
$title = str_replace("<br>","&&br",$title);
$title = str_replace("<","&lt;",$title);
$title = str_replace(">","&gt;",$title);

$description = str_replace("<br>","&&br",$description);
$description = str_replace("<","&lt;",$description);
$description = str_replace(">","&gt;",$description);

$allowed_characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!?.,'\"_-+=/\\~@#$%^&*()[]}{;:©£¢¥®±¿Ø÷×ºµ¦₿ "; // Note that the space at the end of the string is intentional

	$input_string_array = str_split($title);
	$title = "";
	foreach ($input_string_array as $input_string_character) {
	  if (strpos ($allowed_characters, $input_string_character) !== false) {
	    $title = $title . $input_string_character;
	  }
	}

// Fix custom formatting
$title = str_replace("&&br","<br>",$title);
$description = str_replace("&&br","<br>",$description);

if (substr_count($title, "<br>") > 25) {
	echo "Your post can't have over 25 line breaks!";
	echo "<br><a href='/" . $folderName . "'>Back to Main Page</a><br>";
	echo "Your post text is as follows:<br>";
	$title = str_replace("<br>","&&br<br>",$title); // Show the user their post contents so they can copy it and edit it.
	echo $title;
	exit();
}

$latestpost = end($projectsArray);

if ($username == $admin) {
	$customusername = $_POST['customusername'];
	if ($customusername == "") {
		$customusername = $username;
	}
}

if ($username == $admin) {
	array_push($projectsArray, array($latestpost[0] + 1, $title, $customusername, $description, 0, date('Y-m-d H:i:s'), array()));
} else {
	array_push($projectsArray, array($latestpost[0] + 1, $title, $username, $description, 0, date('Y-m-d H:i:s'), array()));
}

file_put_contents('/Library/Server/Web/Data/Sites/Default/' . $folderName . '/projectsArray.txt', serialize($projectsArray)); // Write array changes to disk
$newURL = "/" . $folderName . "/"; // Set redirect to the main page
header('Location: '.$newURL); // Execute redirect
?>
