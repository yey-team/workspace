<?php

if (isset($_GET["login"]) and isset($_GET["email"]) and isset($_GET["password"])) {
    userLogin($_GET["email"], $_GET["password"]);
} else if (isset($_GET["register"]) and isset($_GET["firstname"]) and isset($_GET["lastname"]) and isset($_GET["email"]) and isset($_GET["password"])) {
    userRegister($_GET["firstname"], $_GET["lastname"], $_GET["email"], $_GET["password"]);
}

function userLogin($email, $password)
{
    $isError = false;
    $env = parse_ini_file('.env');
    $usernameDB = $env['USERNAME'];
    $passwordDB = $env['PASSWORD'];
    $DATABASE = $env['DATABASE'];
    $USER = [];

    $connection = mysqli_connect("localhost", $usernameDB, $passwordDB, $DATABASE);
    if (!$connection) {
        echo 'Failed to connect to MySQL';
        $isError = true;
    } else {
        $result = mysqli_query($connection, "SELECT * FROM `users` WHERE `user-email` = '{$email}';");
        if ($result) {
            $USER = $result;
        } else {
            echo 'user-not-exist';
            $isError = true;
        }
    }
    mysqli_close($connection);

    foreach ($USER as $key => $value) {
        print_r($value);
    }


    if (password_verify($password, $value["user-password"])) {
        echo "valid-password";
    } else {
        if ($value["user-email"] == "") {
            echo 'user-not-exist';
        } else {
            echo "not-valid-password";
        }
    }
}

function userRegister($firstname, $lastname, $email, $password)
{
    $isError = false;
    $env = parse_ini_file('.env');
    $usernameDB = $env['USERNAME'];
    $passwordDB = $env['PASSWORD'];
    $DATABASE = $env['DATABASE'];
    $USER_LIST = [];

    $connection = mysqli_connect("localhost", $usernameDB, $passwordDB, $DATABASE);
    if (!$connection) {
        echo 'Failed to connect to MySQL';
        $isError = true;
    } else {
        $result = mysqli_query($connection, "SELECT * FROM `users`");
        $USER_LIST = $result;
    }
    mysqli_close($connection);

    if (!$isError) {
        $UID = uniqid();
        foreach ($USER_LIST as $key => $value) {
            if ($value["user-email"] == $email) {
                echo 'email-exist';
                $isError = true;
                return;
            } else if ($UID == $value["user-id"]) {
                while ($UID == $value["user-id"]) {
                    $UID = uniqid();
                }
            }
        }
        $password = password_hash($password, PASSWORD_DEFAULT);
        if (!$isError) {
            $connection = mysqli_connect("localhost", $usernameDB, $passwordDB, $DATABASE);
            if (!$connection) {
                echo 'Failed to connect to MySQL';
                $isError = true;
            } else {
                $result = mysqli_query($connection, "INSERT INTO `users`(`user-id`, `user-email`, `user-firstname`, `user-lastname`, `user-password`) VALUES ('{$UID}','{$email}','{$firstname}','{$lastname}','{$password}')");
                if ($result) {
                    echo 'Insertion success';
                } else {
                    echo 'Insertion failed: ' . mysqli_error($connection);
                    $isError = true;
                }
            }
        }
        mysqli_close($connection);
    }
}