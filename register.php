
<?php 

    require 'db.php'; 

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $username = trim($_POST["username"]); 
        $email = trim($_POST["email"]); 
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
 
        
        $stmt = $conn->prepare("INSERT INTO user (username, email,  password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email,  $password); 
        
        if($stmt->execute()){
            echo "Regestration successfully. <a href='login.php'>Login here</a>"; 
        }
        else{
            echo "Error: Username might already exists."; 
        }
    }
?>

<form method="post">
    <input type="text" name="username" required placeholder="Username" /><br>
    <input type="email" name="email" required placeholder="email" /><br>
    <input type="password" name="password" required placeholder="Password" /><br>
    <button type="submit" >Register</button>
</form>