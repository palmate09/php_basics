<?php
session_start(); 
require 'db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifier = trim($_POST["identifier"]);
    $password = $_POST["password"]; 

    $stmt = $conn->prepare("SELECT id, password FROM user WHERE username = ? OR email = ?"); 
    $stmt->bind_param("ss", $identifier, $identifier); 
    $stmt->execute(); 
    $stmt->store_result(); 

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $hashed_password); 
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id; 
            $_SESSION['identifier'] = $identifier; 
            header("Location: dashboard.php"); 
            exit; 
        } else {
            echo "❌ Invalid password."; 
        }
    } else {
        echo "❌ No user found with that username/email."; 
    }
}
?>


<form method="post">
    <input type="text" name="identifier" required placeholder="Username or Email" /><br>
    <input type="password" name="password" required placeholder="Password" /><br>
    <button type="submit">Login</button>
</form>