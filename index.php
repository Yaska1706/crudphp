<html>
    <head>
        <title>View Records</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    </head>
<body>

    <h1>View Records</h1>



<?php
// connect to the database
include('db.php');

// get the records from the database
if ($result = $mysqli->query("SELECT * FROM players ORDER BY PlayerID"))
{
// display records if there are records to display
if ($result->num_rows > 0)
{
// display records in a table
echo "<table border='1' cellpadding='10'>";

// set table headers
echo "<tr><th>PlayerID</th><th>First Name</th><th>Last Name</th><th></th><th></th></tr>";

while ($row = $result->fetch_object())
{
// set up a row for each record
echo "<tr>";
echo "<td>" . $row->PlayerID . "</td>";
echo "<td>" . $row->FirstName . "</td>";
echo "<td>" . $row->SecondName . "</td>";
echo "<td><a href='records.php?id=" . $row->PlayerID . "'>Edit</a></td>";
echo "<td><a href='delete.php?id=" . $row->PlayerID . "'>Delete</a></td>";
echo "</tr>";
}

echo "</table>";
}
// if there are no records in the database, display an alert message
else
{
echo "No results to display!";
}
}
// show an error if there is an issue with the database query
else
{
echo "Error: " . $mysqli->error;
}

// close database connection
$mysqli->close();

?>

<a href="addplayer.php">Add New Record</a>
</body>
</html>

