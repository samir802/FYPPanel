<?php
include('database/db.php');
include('global.php');

$image = $_FILES['image']['name'];
$imagePath = "uploads/" . $image;
move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
$sql = "UPDATE doctors SET display_image='$image' WHERE id = 3";
$conn->query($sql);
