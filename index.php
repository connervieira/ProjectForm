<?php
include "/Library/Server/Web/Data/Sites/Default/analytics.php"; // This uses the V0LT analytics system, and will have to be either removed, or replaced with your own authentication system.
logVisit(); // Executes the logging of a visit through the V0LT analytics system.


// Configuration:
$admin = "cvieira"; // This is the username of the admin. This user will get special privileges.
$folderName = "cntprojects"; // This is the name of the parent folder of the project system.

// This uses the V0LT login system, and will have to be replaced for other sites
session_start();
if (isset($_SESSION['loggedin'])) {
	$username = $_SESSION['username']; // If user is signed in, determine their username for later use
} else {
	header("Location: /login.php"); // If user isn't signed in, redirect them to the sign in page
	exit();
}
// To replace the authentication system above, all you need to do is set '$username' to the username of the user currently signed in.

$projectsArray = unserialize(file_get_contents('/Library/Server/Web/Data/Sites/Default/' . $folderName . '/projectsArray.txt'));
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>V0LT - Auburn CNT Projects</title>
	<link rel="stylesheet" href="https://v0lttech.com/assets/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://v0lttech.com/assets/fonts/ionicons.min.css">
	<link rel="stylesheet" href="https://v0lttech.com/assets/css/Features-Blue.css">
	<link rel="stylesheet" href="https://v0lttech.com/assets/css/Header-Blue.css">
	<link rel="stylesheet" href="https://v0lttech.com/assets/css/Highlight-Phone.css">
	<link rel="stylesheet" href="https://v0lttech.com/assets/css/Login-Form-Dark.css">
	<link rel="stylesheet" href="https://v0lttech.com/assets/css/styles.css">
	<link href="https://v0lttech.com/assets/css/simple-sidebar.css" rel="stylesheet">
	<link href="https://v0lttech.com/assets/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="width:100%;background:#191919;margin:0%;">
	<div id="wrapper">
		<div class="login-dark" style="height:768px;padding-bottom:1000px;background-attachment:fixed;">
			<a class="btn btn-primary" role="button" href="/" style="margin-top:24px;margin-bottom:-57px;margin-left:30px;background-color:#888888;border-color:#111111;">Back</a>
			<a class="btn btn-primary" role="button" href="https://github.com/connervieira/ProjectForm" style="margin-top:24px;margin-bottom:-57px;margin-left:30px;background-color:#888888;border-color:#111111;">Source Code</a>
		<br>
		<div>
			<form action="create.php" data-id="form--1" style="margin-top:115px;" method="post" enctype="multipart/form-data">
				<input class="form-control" type="text" name="title" autocomplete="off" placeholder="Project Title">
				<input class="form-control" type="text" name="description" autocomplete="off" placeholder="Project Description">
				<?php
				if ($username == $admin) {
					echo '<input class="form-control" type="text" name="customusername" placeholder="Custom Username">'; // This will only appear for the admin, so they can create tests posts
				}
				?>
				<br>
				<div class="form-group" style="margin-bottom:0px;">
				<input class="btn btn-primary btn-block" type="submit" value="Submit Project" style="background-color:#5E5E5E;border-color:#111111;">

				</div>
			</form>
		</div>
        <h1 style='text-align:center;color:white;padding:0px;font-weight:lighter;font-size:100px;padding-bottom:0px;color:#ffffff;margin-top:100px;'>AUBURN CNT PROJECTS</h1>
	</div>

	<div class="features-blue" style="width:100%;background:linear-gradient(135deg, #172a74, #21a9af">
		<div class="container" style="width:100%">
			<div class="intro" style="width:100%">
				<h2 class="text-center" style="font-weight:lighter;font-size:60px;" data-bs-hover-animate="pulse">Projects</h2>
				<?php
				if ($_GET["user"] !== null) {
					echo '<p class="text-center" style="font-weight:lighter;font-size:40px;" data-bs-hover-animate="pulse">Here are all the projects by the user \'' . $_GET["user"] . '\' in the Auburn CNT program!</p>';
				} else {
					echo '<p class="text-center" style="font-weight:lighter;font-size:40px;" data-bs-hover-animate="pulse">Here are all the projects already made, or currently being worked on by the Auburn CNT program!</p>';
				}
				?>
				<div style="text-align:center;width:100%;">
                <?php
					foreach (array_reverse($projectsArray) as $postsArrayEntry) {
							if ($_GET["user"] == $postsArrayEntry[2] || $_GET["user"] == null) {
								echo "<br><br><br><br><br>";
									echo "<div style='background:#1D2126;class=card;border-radius:10px;'>";
								echo "<div class='card-body' data-id='div-$postsArrayEntry[0]'>"; // Create post container

								    echo "<a href='social.php?user=$postsArrayEntry[2]'><h1 class='card-title' style='color:#ffffff;font-size:30px;font-weight:lighter;'>$postsArrayEntry[2]</h1></a>"; // Username text

								echo "<h6 class='card-title' style='color:lightgray;font-size:1rem;'>$postsArrayEntry[5]</h1>"; // Date
								echo "<h6 class='card-title' style='color:gray'>Post ID: $postsArrayEntry[0]</h6>"; // Post ID
                                echo "<hr>";
								echo "<h6 class='card-title' style='color:lightgray;font-size:3rem;'>$postsArrayEntry[1]</h1>"; // Project Title
								echo "<h6 class='card-title' style='color:lightgray;font-size:1rem;'>$postsArrayEntry[3]</h1>"; // Project Description

								if ($postsArrayEntry[2] == $username) { // If the username associated with the post is the same as the username of the user currently signed in, show them the delete button to delete their post.
									echo "<a href='delete.php?posttodelete=" . $postsArrayEntry[0] . "'><input type='button' value='Delete' style='margin-top:5px;background-color:#5E5E5E;border-color:#111111;' class='btn btn-primary btn-block'></a>";
								} else if ($username == "cvieira") { // Always show the delete button if the user is the admin.
									echo "<a href='delete.php?posttodelete=" . $postsArrayEntry[0] . "'><input type='button' value='Delete' style='margin-top:5px;background-color:#bb1111;border-color:#111111;' class='btn btn-primary btn-block'></a>";
								}
								echo "</div>";
								echo "</div>";
						}

					}
					?>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
