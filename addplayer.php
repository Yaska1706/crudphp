<?php
/*
Allows the user to both create new records and edit existing records
*/

// connect to the database
include("db.php");

// creates the new/edit record form
// since this form is used multiple times in this file, I have made it a function that is easily reusable
function renderForm($first = '', $last ='', $error = '', $id = '')
{ ?>
<!DOCTYPE HTML >
<html>
<head>
<title>
<?php if ($id != '') { echo "Edit Record"; } else { echo "New Record"; } ?>
</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
<h1><?php if ($id != '') { echo "Edit Record"; } else { echo "New Record"; } ?></h1>
<?php if ($error != '') {
echo "<div style='padding:4px; border:1px solid red; color:red'>" . $error
. "</div>";
} ?>

<form action="" method="post">
<div>
<?php if ($id != '') { ?>
<input type="hidden" name="id" value="<?php echo $id; ?>" />
<p>ID: <?php echo $PlayerID; ?></p>
<?php } ?>

<strong>First Name: *</strong> <input type="text" name="firstname"
value="<?php echo $first; ?>"/><br/>
<strong>Second Name: *</strong> <input type="text" name="lastname"
value="<?php echo $last; ?>"/>

<input type="submit" name="submit" value="Submit" />
</div>
</form>
</body>
</html>

<?php }



/*

EDIT RECORD

*/
// if the 'id' variable is set in the URL, we know that we need to edit a record
if (isset($_GET['id']))
{
// if the form's submit button is clicked, we need to process the form
if (isset($_POST['submit']))
{
// make sure the 'id' in the URL is valid
if (is_numeric($_POST['id']))
{
// get variables from the URL/form
$id = $_POST['PlayerID'];
$firstname = htmlentities($_POST['FirstName'], ENT_QUOTES);
$lastname = htmlentities($_POST['SecondName'], ENT_QUOTES);

// check that firstname and lastname are both not empty
if ($FirstName == '' || $SecondName == '')
{
// if they are empty, show an error message and display the form
$error = 'ERROR: Please fill in all required fields!';
renderForm($FirstName, $SecondName, $error, $PlayerID);
}
else
{
// if everything is fine, update the record in the database
if ($stmt = $mysqli->prepare("UPDATE players SET FirstName = ?, SecondName = ?
WHERE PlayerID=?"))
{
$stmt->bind_param("ssi", $FirstName, $SecondName, $PlayerID);
$stmt->execute();
$stmt->close();
}
// show an error message if the query has an error
else
{
echo "ERROR: could not prepare SQL statement.";
}

// redirect the user once the form is updated
header("index.php");
}
}
// if the 'id' variable is not valid, show an error message
else
{
echo "Error!";
}
}
// if the form hasn't been submitted yet, get the info from the database and show the form
else
{
// make sure the 'id' value is valid
if (is_numeric($_GET['id']) && $_GET['id'] > 0)
{
// get 'id' from URL
$id = $_GET['id'];

// get the recod from the database
if($stmt = $mysqli->prepare("SELECT * FROM players WHERE PlayerID=?"))
{
$stmt->bind_param("i", $PlayerID);
$stmt->execute();

$stmt->bind_result($PlayerID, $FirstName, $SecondName);
$stmt->fetch();

// show the form
renderForm($FirstName, $SecondName, NULL, $PlayerID);

$stmt->close();
}
// show an error if the query has an error
else
{
echo "Error: could not prepare SQL statement";
}
}
// if the 'id' value is not valid, redirect the user back to the view.php page
else
{
header("index.php");
}
}
}



/*

NEW RECORD

*/
// if the 'id' variable is not set in the URL, we must be creating a new record
else
{
// if the form's submit button is clicked, we need to process the form
if (isset($_POST['submit']))
{
// get the form data
$firstname = htmlentities($_POST['FirstName'], ENT_QUOTES);
$lastname = htmlentities($_POST['SecondName'], ENT_QUOTES);

// check that firstname and lastname are both not empty
if ($FirstName == '' || $SecondName == '')
{
// if they are empty, show an error message and display the form
$error = 'ERROR: Please fill in all required fields!';
renderForm($FirstName, $SecondName, $error);
}
else
{
// insert the new record into the database
if ($stmt = $mysqli->prepare("INSERT players (FirstName, SecondName) VALUES (?, ?)"))
{
$stmt->bind_param("ss", $FirstName, $SecondName);
$stmt->execute();
$stmt->close();
}
// show an error if the query has an error
else
{
echo "ERROR: Could not prepare SQL statement.";
}

// redirec the user
header("index.php");
}

}
// if the form hasn't been submitted yet, show the form
else
{
renderForm();
}
}

// close the mysqli connection
$mysqli->close();
?>