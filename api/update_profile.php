<?php
include ('../database/db.php');
include ('../baseLink.php');

$token = $_GET['token'];
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$address = $_POST['address'];

// Retrieve the user profile based on the provided token
$stmt = $conn->prepare("SELECT users.id FROM users INNER JOIN api_tokens ON users.id = api_tokens.user_id WHERE api_tokens.token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();

// Get the result
$result = $stmt->get_result();
$userId = null;
while ($row = $result->fetch_assoc()) {
    $userId = $row['id'];
}

// Update the user's image if $image has a value
if (!empty($_FILES['image']['name'])) {
    $image = $_FILES['image']['name'];
    $imagePath = "../uploads/" . $image;
    move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);

    // Update the user's image
    $sql = "UPDATE users SET image=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $image, $userId);
    $stmt->execute();

    // Check for success
    if ($stmt->affected_rows > 0) {
        // Echo JSON response
        echo json_encode(["message" => "Image uploaded successfully."]);
    } else {
        // Echo JSON response
        echo json_encode(["message" => "Error uploading image."]);
    }

    $stmt->close();
} else {
    // Update the user's profile details only if image is not uploaded
    $sql1 = "UPDATE users SET name = ?, email = ?, phone = ?, address = ? WHERE id=?";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->bind_param("ssssi", $name, $email, $phone, $address, $userId);
    $stmt1->execute();

    // Check for success
    if ($stmt1->affected_rows > 0) {
        // Echo JSON response
        echo json_encode(["message" => "Profile details updated successfully."]);
    } else {
        // Echo JSON response
        echo json_encode(["message" => "Error updating profile."]);
    }

    $stmt1->close();
}

$conn->close();
