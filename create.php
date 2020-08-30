<?php

// This uses the V0LT authentication system, and will need to be replaced.
session_start();
if (isset($_SESSION['loggedin'])) {
	$username = $_SESSION['username'];
} else {
	header("Location: /login.php"); // If the user is not signed in, redirect them to the sign in page.
	exit();
}
// To replace the authentication system above, all you need to do is set '$username' to the username of the user currently signed in.

// Configuration:
$whitelist = Array("cvieira"); // This is a whitelist of which users can make posts.
$admin = "cvieira"; // This is the username of the admin. This user will get special privileges.
$folderName = "cntprojects"; // This is the name of the parent folder of the projects system.




if (!in_array($username, $whitelist)) { // Check to see if the current user is in the whitelist
    echo "You are not whitelisted to use this project board. Please contact the admin if this is an error.";
    echo "<br><a href='" . $folderName . "'>Back to Main Page.</a>";
    exit();
}

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
$title = str_replace("<br>","&&br",$title); // Replace <br> tags with an indicator that will be replaced after sanitizing inputs. This will allow for the <br> tag to be used in posts.
$title = str_replace("<","&lt;",$title); // Replace < with an HTML character that won't be parsed as code
$title = str_replace(">","&gt;",$title); // Replace > with an HTML character that won't be parsed as code

$description = str_replace("<br>","&&br",$description); // Replace <br> tags with an indicator that will be replaced after sanitizing inputs. This will allow for the <br> tag to be used in posts.
$description = str_replace("<","&lt;",$description); // Replace < with an HTML character that won't be parsed as code
$description = str_replace(">","&gt;",$description); // Reokace > with an HTML character that won't be parsed as code

$allowed_characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!?.,'\"_-+=/\\~@#$%^&*()[]}{;:©£¢¥®±¿Ø÷×ºµ¦₿ "; // Note that the space at the end of the string is intentional, as a space is an allowed character. Feel free to add or remove any characters that you do or don't want to allow.

	$input_string_array = str_split($title);
	$title = "";
	foreach ($input_string_array as $input_string_character) {
	  if (strpos ($allowed_characters, $input_string_character) !== false) {
	    $title = $title . $input_string_character;
	  }
	}

// Fix custom formatting to allow the <br> tag to be used.
$title = str_replace("&&br","<br>",$title);
$description = str_replace("&&br","<br>",$description);

if (substr_count($title, "<br>") > 25) { // Limit the amount of line breaks that can be used to prevent spam.
	echo "Your post can't have over 25 line breaks!";
	echo "<br><a href='/" . $folderName . "'>Back to Main Page</a><br>";
	echo "Your post text is as follows:<br>";
	$title = str_replace("<br>","&&br<br>",$title); // Show the user their post contents so they can copy it and edit it.
	echo $title;
	exit();
}

$latestpost = end($projectsArray);

if ($username == $admin) { // Only allow the admin to enter a custom username
	$customusername = $_POST['customusername'];
	if ($customusername == "") { // If no custom username was provided, just use their real username
		$customusername = $username;
	}
}

if ($username == $admin) { // If the user is an admin, use the the custom username as defined above.
	array_push($projectsArray, array($latestpost[0] + 1, $title, $customusername, $description, 0, date('Y-m-d H:i:s'), array()));
} else { // If the user is a standard user, use their actual username.
	array_push($projectsArray, array($latestpost[0] + 1, $title, $username, $description, 0, date('Y-m-d H:i:s'), array()));
}

file_put_contents('/Library/Server/Web/Data/Sites/Default/' . $folderName . '/projectsArray.txt', serialize($projectsArray)); // Write array changes to disk
$newURL = "/" . $folderName . "/"; // Set redirect to the main page
header('Location: '.$newURL); // Execute redirect
?>
