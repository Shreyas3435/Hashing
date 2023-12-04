<?php
session_start();

include("connection.php");
include("functions.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];

    if (!empty($user_name) && !empty($password) && !is_numeric($user_name)) {

        $salt = generate_salt(); 
        $salted_password = $password . $salt;

        $hashed_password = md5($salted_password);

       
        $encrypted_password = simple_xor_encrypt($hashed_password, $salt);

        
        $user_id = random_num(20);
        $query = "INSERT INTO users (user_id, user_name, password, salt) VALUES (?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "ssss", $user_id, $user_name, $encrypted_password, $salt);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header("Location: login.php");
        die;
    } else {
        echo "Please enter some valid information!";
    }
}


function generate_salt($length = 16) {
    return bin2hex(random_bytes($length));
}


function simple_xor_encrypt($data, $key) {
    $result = '';
    for ($i = 0; $i < strlen($data); $i++) {
        $result .= $data[$i] ^ $key[$i % strlen($key)];
    }
    return $result;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>
</head>
<body>

    <style type="text/css">
    
    #text{

        height: 25px;
        border-radius: 5px;
        padding: 4px;
        border: solid thin #aaa;
        width: 100%;
    }

    #button{

        padding: 10px;
        width: 100px;
        color: white;
        background-color: lightblue;
        border: none;
    }

    #box{

        background-color: grey;
        margin: auto;
        width: 300px;
        padding: 20px;
    }

    </style>

    <div id="box">
        
        <form method="post">
            <div style="font-size: 20px;margin: 10px;color: white;">Signup</div>

            <input id="text" type="text" name="user_name"><br><br>
            <input id="text" type="password" name="password"><br><br>

            <input id="button" type="submit" value="Signup"><br><br>

            <a href="login.php">Click to Login</a><br><br>
        </form>
    </div>
</body>
</html>
