<?php
/*
 * Just a little snippet to include on every page where authentication is needed
 */
if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}
if(!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] != 1)
{
    //try http digest authentication
    require "http_digest.php";

    //if still not logged in...
    if($_SESSION["logged_in"] != 1)
    {
        die();
    }
}