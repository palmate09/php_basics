
<?php 

    session_start(); 
    require 'db.php'; 

    if(!isset($_SESSION['user_id'])){
        header("Location: login.php"); 
        exit; 
    }

    $userId = $_SESSION['user_id']; 

    if($_SERVER["REQUEST_METHOD"] == "GET"){
        $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
        $stmt->bind_param("i", $userId); 
        $stmt->execute(); 
        $result = $stmt->get_result(); 

        if($result->num_rows == 1){
            $user = $result->fetch_assoc(); 
        }
        else{
            echo "user not found";
            exit; 
        }
    }

?>



<!DOCTYPE html>
<html>
<head>
    <title>Profile Page</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 40px;
            background-color: #f5f5f5;
        }
        .profile {
            background: white;
            padding: 20px;
            max-width: 400px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .profile h2 {
            margin-top: 0;
        }
        .logout {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="profile">
    <h2>Welcome, <?= htmlspecialchars($user['username']) ?>!</h2>
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
    <p><strong>Member since:</strong> <?= htmlspecialchars($user['created_at']) ?></p>

    <div class="logout">
        <a href="logout.php">Logout</a>
    </div>

    <div class="update">
        <a href="
    </div>
</div>

</body>
</html>