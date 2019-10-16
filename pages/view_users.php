<?php

$page_title = 'View the Current Users';
include('includes/header.html');
?>
<div class = "col-sm-10">

<h1>Registered Users</h1>

<?php

require('includes/pagination_functions.inc.php'); // Get pagination functions

define('DISPLAY', 20); // Number of records to show per page

$pages = getPagesValue('id', 'users');
$start = getStartValue();

list($sort, $order_by, $direction, $order_in) = getSortValue('users');
define('UPARR', '<a href = "../view_users?sort='. $sort . '&dirtn=do">&#x25B2;</a>');
define('DOWNARR', '<a href = "../view_users?sort=' . $sort . '&dirtn=up">&#x25BC;</a>');
define('ARR', $direction == 'do' ? DOWNARR : UPARR);
define('LNARR', isset($_GET['sort']) && $_GET['sort'] == 'ln' ? ARR : '');
define('FNARR', isset($_GET['sort']) && $_GET['sort'] == 'fn' ? ARR : '');
define('RDARR', isset($_GET['sort']) && $_GET['sort'] == 'rd' ? ARR : '');

$q = 'SELECT last_name, first_name, DATE_FORMAT(registration_date, \'%M %d, %Y\') AS dr, id FROM users';
$result = performPaginationQuery($dbc, $q, $order_by, $start);

// Table header
$adminControls = ISADMIN ? '<th align = "left"><strong>Edit</strong></th>
<th align = "left"><strong>Delete</strong></th>' : '';
echo '<table id = "users-table" width = "100%">
<thead>
<tr>'.
$adminControls . '
<th align = "left"><strong>User Profile</strong></th>
<th align = "left"><strong><a href = "view_users?sort=ln">Last Name</a>'.LNARR.'</strong></th>
<th align = "left"><strong><a href = "view_users?sort=fn">First Name</a>'.FNARR.'</strong></th>
<th align = "left"><strong><a href = "view_users?sort=rd">Date Registered</a>'.RDARR.'</strong></th>
</tr>
</thead>
<tbody>
';

// Fetch and print all the records

$bg = 'one'; // Set the initial background color

while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) { // Loop through the records in an associative array

	$bg = ($bg == 'one' ? 'two' : 'one'); // Switch the background color every row
	$adminControls = ISADMIN ? '
	<td class = "links ' . $bg . '" align = "left"><a href = "pages/edit_user.php?id=' . $row['id'] . '">Edit</a></td>
	<td class = "links ' . $bg . '" align = "left"><a href = "pages/delete_user.php?id=' . $row['id'] . '">Delete</a></td>' : '';

	echo '<tr>' . 
	$adminControls . '
	<td class = "links ' . $bg . '" align = "left"><a href = "users/' . $row['id'] . '">View Profile</a></td>
	<td class = "output ' . $bg . '" align = "left">' . $row['last_name'] . '</td>
	<td class = "output ' . $bg . '" align = "left">' . $row['first_name'] . '</td>
	<td class = "output ' . $bg . '" align = "left">' . $row['dr'] . '</td>
	</tr>
	';

}

echo '</tbody></table>';
mysqli_free_result ($result);
mysqli_close($dbc);

// Make the links to other pages, if necessary
setPreviousAndNextLinks('view_users');

include('includes/footer.html');

?>

