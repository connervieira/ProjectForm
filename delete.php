<?php

// This uses the default V0LT authentication system, and will need to be replaced.
session_start();
if (isset($_SESSION['loggedin'])) {
    $username = $_SESSION['username'];
} else {
    header("Location: /login.php"); // If the user is not signed in, send them to the login page.
    exit();
}

$admin = "cvieira"; // This is the username of the admin. This user will get special privileges.
$folderName = "cntprojects"; // This is the name of the parent folder of the projects system.

$postsArray = unserialize(file_get_contents('/Library/Server/Web/Data/Sites/Default/' . $folderName . '/projectsArray.txt'));

$idToDelete = (int)$_GET['posttodelete'] - 1;

if ($postsArray[$idToDelete][2] == $username || $username == $admin) { // If the username associated with the post the user is trying to delete doesn't match the username of the user, then only let them delete it if they are the admin 
  unset($postsArray[$idToDelete]);
  echo "<p>Post deleted successfully!</p>";
  echo "<br><a href='/" . $folderName . "'>Back to Posts</a>";
} else {
  echo "<p>You do not have permission to delete other user's posts!</p>";
  echo "<p><a href='/" . $foldername . "'>Back to Main Page</a></p>";
  exit();
}

file_put_contents('/Library/Server/Web/Data/Sites/Default/' . $folderName . '/projectsArray.txt', serialize($postsArray));

$newURL = "/" . $folderName; // Set redirect to the main page
header('Location: '.$newURL); // Execute redirect
?>
