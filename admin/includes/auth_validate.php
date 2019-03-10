<?php
if (!isset($_SESSION['user_logged_in']))
{
    // If user is Not Logged in, redirect to login.php page.
	header('Location:'.ADMIN.'login.php');
}
else
{
    // If User is logged in the session['user_logged_in'] will be set to true    
}
?>