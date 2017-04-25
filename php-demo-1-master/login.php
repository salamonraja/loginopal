<!DOCTYPE html>
<?php
/* **************************************
 * This script check the stored value
 * against the supplied value from the
 * form
 * *************************************/
 
// We start with resetting all the variables and arrays before working with them
// Otherwise we might get unwanted data in our script.
$database = NULL;
$login = NULL;
$connection = NULL;


// We define the $database variable as an array
$database = array();

// Then we include the config.php script that holds the information we will need.
require_once 'config.php';

// We define the $login variable as an array
$login = array();



/* It's time to transfer the data from the form to the $login array so that we can
 * start to work with it.
 * */
$login['name'] = $_POST['name'];

// We add the $salt to the password before encrypting it.
$login['password'] = $_POST['password'] . $salt;

/* Then we encrypt the password with the sha1 function.
 * This will create a 40 character string.
 * */
$login['password'] = sha1($login['password']);



// Now it's time to create the database server connection and store it in a variable for easy access.
$connection = mysql_connect($database['host'], $database['user'], $database['pass']);

// Incase the database server connection fails, we better report it.
if (!$connection):
	die('Unable to connect to the database: ' . mysql_error());
endif;

// We select what database we want to access, and if it fails, we report the error.
mysql_select_db($database['database'], $connection) or die(mysql_error());

$result = NULL;
$result = array();
/* we check if the supplied username is present in the database, and retrive all the relevant information. 
 * 'password, email' can be replaced by '*' to fetch ALL information stored in the same row as the username.
 * If the querry fails, we report it. 
 * */
$result = mysql_query("SELECT password, email FROM users WHERE name = '".$login['name']."'") or die(mysql_error());

/*  We move all the retrived data over to an array so we can work with it.
 * */
$row = NULL;
$row = mysql_fetch_assoc($result);

/* we check if the stored encrypted password matches the encrypted one supplied in the form.
 * */
$success = NULL;
if ($row['password'] == $login['password']):
	$success = TRUE;
else:
	$success = FALSE;
endif;

// This function will print the entire array, so that we can see the content, it's usually only used while debugging and writing code.
print_r($row);

// Below we print out the result of our script. If the supplied username don't match anything in the database, the $row['password'] will be empty.
?>
<html>
	<body>
		<?php if($success == TRUE): ?>
		<p>Loggnin successfull! Welcome <?php echo $login['name']; ?>!</p>
		<?php else: ?>
		<p>incorrect username or password, please try again!</p>
		<?php endif; ?>
		<p>Database: <?php echo $row['password'] ?></p>
		<p>Form: <?php echo $login['password'] ?></p>
	</body>
</html>
