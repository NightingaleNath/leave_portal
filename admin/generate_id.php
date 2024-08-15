<?php include('../includes/config.php'); ?>
<?php include('../includes/session.php');?>

<?php
// Fetch the last assigned staff ID
$query = "SELECT staff_id FROM tblemployees ORDER BY staff_id DESC LIMIT 1";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $lastId = $row['staff_id'];
    $lastNumber = (int)substr($lastId, 4); // Extract the numeric part of the ID
    $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT); // Increment and format the number
} else {
    $newNumber = '001';
}

$newId = 'LLM ' . $newNumber;
echo $newId;
?>
