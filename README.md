# ProjectForm
A simple project sign up form I made for a class

## Features
### Straight-forward
ProjectForm is very easy and straight-forward to use, so you won't have to take the time to teach your audience how to use it. Just enter a project title, project description, and you're good to go!

### Secure
ProjectForm uses a character whitelist, as well as input sanitization to ensure that no malicious code can be injected into the form.

### No External Dependencies
ProjectForm doesn't require any third party services, and can run entirely on your server. By default, stylesheets are loaded from V0LT, but these can be cloned to your server should you want to selfhost completely.

### Easily Substituted Authentication System
ProjectForm is built to run on the V0LT AUTHv2 system, which is extremely simple and easy to replace. All you need is to provide the current user's username to a PHP variable.

## Set Up
ProjectForm is built to be run on V0LT, but it can be adapted if you'd like to run it yourself. This is what you'll need to change.
* index.php, create.php, and delete.php all have variables that need to be changed.
  * $whitelist is a list of users who can post
  * $admin is the site admin
  * $folderName is the name of your project folder (the parent folder of the project)
* You'll also need to replace the V0LT authentication system at the top of each of the 3 pages. Simply set '$username' to the current user's username at the beginning of each page.
* If desired, clone the stylesheets linked from the V0LT server in index.php to your server. This step is optional.
