<?php
/* **************************************
 * This script is used to register the new
 * user, and store the userinformation
 * in the database.
 * *************************************/
 
// We start with resettign the variables we will use, so that we wont get unwanted data.
$database = NULL;
$register = NULL;
$connection = NULL;


// we define $database as an array
$database = array();

// we then include the config.php file that contains the relevant database information.
require_once 'config.php';

// we define $register as an array.
$register = array();



// Time to move all relevant data over too the $register array before working with it.
$register['name'] = $_POST['name'];

// Vi lägger på ett 'salt' och användarnamnet efter lösenordet för att få lite säkerhet.
$register['password'] = $_POST['password'] . $salt;

// Sedan krypterar vi lösenordet med krypto funktionen sha1
$register['password'] = sha1($register['password']);

// Och vi avslutar med att flytta över email adressen
$register['email'] = $_POST['email'];



// Nu är det dags att skapa en anslutning till databasen
$connection = mysql_connect($database['host'], $database['user'], $database['pass']);

// Om anslutningen till servern misslyckades så är det bäst att vi rapporterar det
if (!$connection):
	die('Kunde inte ansluta till servern: ' . mysql_error());
endif;

// Vi väljer vilken databas vi vill lägga in datan i
mysql_select_db($database['database'], $connection) or die(mysql_error());

// Vi lägger in den data vi har samlat i rätt tabell
mysql_query("INSERT INTO users (name, password, email) VALUES ('".$register['name']."', '".$register['password']."', '".$register['email']."')") or die(mysql_error());


// När vi är klar så stänger vi anslutningen till servern, så vi inte skapar onödig belastning
mysql_close($connection);

echo 'Registrering klar!';
