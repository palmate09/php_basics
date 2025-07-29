
<?php 

    session_start(); 
    require 'db.php'; 

    if(!isset($_SESSION['user_id'])){
        header('Location: login.php'); 
        exit; 
    }

    $userId = $_SESSION['user_id']; 


    if(isset($_POST["update_profile"])){
        $newUsername = trim($_POST["username"]); 
        $newEmail = trim($_POST["email"]); 

        if(!empty($newUsername) && !empty($newEmail)){
            $stmt = $conn->prepare("UPDATE user SET username = ?, email = ? WHERE id = ?"); 
            $stmt->bind_param("ssi", $newUsername, $newEmail, $userId); 
            
            if($stmt->execute()){
                echo "Profile updated successfully";
            }
            else{
                echo "Invalid Profile";
            }
            $stmt->close(); 
        }
    }


    if(isset($_POST["change_password"])){
        $currentPassword = $_POST['current_password']; 
        $newPassword = $_POST['new_password']; 

        $stmt = $conn->prepare("SELECT password FROM user WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($storedHashedPassword);
        $stmt->fetch();
        $stmt->close();


        if (password_verify($currentPassword, $storedHashedPassword)) {
            $newHashed = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE user SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $newHashed, $userId);

            if ($stmt->execute()) {
                $_SESSION['password_success'] = "Password changed successfully!";
            } else {
                $_SESSION['password_error'] = "Failed to change password.";
            }
                $stmt->close();
        } else {
            $_SESSION['password_error'] = "Current password is incorrect.";
        }
    }


    $stmt = $conn->prepare("SELECT username, email, created_at FROM user WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

?>  



<!DOCTYPE html>
<html>
<head>
    <title>Your Profile</title>
    <style>
        body {
            font-family: sans-serif;
            background: #f4f4f4;
            padding: 30px;
        }
        .profile-box {
            max-width: 400px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        input {
            width: 100%;
            padding: 8px;
            margin-top: 8px;
            margin-bottom: 16px;
        }
        button {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            width: 100%;
        }
        .message {
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>

<div class="profile-box">
    <h2>Welcome, <?= htmlspecialchars($user['username']) ?></h2>

    <!-- Success/Error Messages -->
    <?php if (isset($_SESSION['update_success'])): ?>
        <p class="message"><?= $_SESSION['update_success']; unset($_SESSION['update_success']); ?></p>
    <?php endif; ?>
    <?php if (isset($_SESSION['update_error'])): ?>
        <p class="error"><?= $_SESSION['update_error']; unset($_SESSION['update_error']); ?></p>
    <?php endif; ?>

    <!-- Update Username & Email -->
    <form method="POST">
        <input type="hidden" name="update_profile" value="1" />
        <label>Username:</label>
        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

        <button type="submit">Update Profile</button>
    </form>

    <hr>

    <!-- Success/Error Messages -->
    <?php if (isset($_SESSION['password_success'])): ?>
        <p class="message"><?= $_SESSION['password_success']; unset($_SESSION['password_success']); ?></p>
    <?php endif; ?>
    <?php if (isset($_SESSION['password_error'])): ?>
        <p class="error"><?= $_SESSION['password_error']; unset($_SESSION['password_error']); ?></p>
    <?php endif; ?>

    <!-- Change Password -->
    <form method="POST">
        <input type="hidden" name="change_password" value="1" />
        <label>Current Password:</label>
        <input type="password" name="current_password" required>

        <label>New Password:</label>
        <input type="password" name="new_password" required>

        <button type="submit">Change Password</button>
    </form>

    <hr>
    <p><strong>Joined:</strong> <?= htmlspecialchars($user['created_at']) ?></p>
    <a href="logout.php">Logout</a>
</div>

</body>
</html>
