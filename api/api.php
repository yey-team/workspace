<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: *');
header('Content-Type: application/json');

if (isset($_GET["login"]) && isset($_GET["email"]) && isset($_GET["password"])) {
    userLogin($_GET["email"], $_GET["password"]);
} elseif (isset($_GET["register"]) && isset($_GET["firstname"]) && isset($_GET["lastname"]) && isset($_GET["email"]) && isset($_GET["password"])) {
    userRegister($_GET["firstname"], $_GET["lastname"], $_GET["email"], $_GET["password"]);
} elseif (isset($_GET["tokenCheck"]) && isset($_GET["token"])) {
    tokenCheck($_GET["token"]);
} elseif (isset($_GET["tokenCreation"]) && isset($_GET["user_id"])) {
    tokenCreation($_GET["user_id"]);
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
        echo json_encode(array("status" => "failed-to-connect", "error" => mysqli_connect_error()));
        $isError = true;
    } else {
        $result = mysqli_query($connection, "SELECT * FROM `users` WHERE `user-email` = '{$email}';");
        if ($result) {
            $USER = mysqli_fetch_assoc($result);
        } else {
            echo json_encode(array("status" => "user-not-exist", "error" => mysqli_error($connection)));
            $isError = true;
        }
    }
    mysqli_close($connection);

    if (!$isError) {
        if (password_verify($password, $USER["user-password"])) {
            echo json_encode(array("status" => "valid-password", "user" => $USER));
        } else {
            echo json_encode(array("status" => "not-valid-password", "error" => "Invalid password"));
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
        echo json_encode(array("status" => "failed-to-connect", "error" => mysqli_connect_error()));
        $isError = true;
    } else {
        $result = mysqli_query($connection, "SELECT * FROM `users`");
        $USER_LIST = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    mysqli_close($connection);

    if (!$isError) {
        $UID = generateUniqueId();
        foreach ($USER_LIST as $value) {
            if ($value["user-email"] == $email) {
                echo json_encode(array("status" => "email-exist", "error" => "Email already exists"));
                $isError = true;
                return;
            } else if ($UID == $value["user-id"]) {
                while ($UID == $value["user-id"]) {
                    $UID = generateUniqueId();
                }
            }
        }
        $password = password_hash($password, PASSWORD_DEFAULT);
        if (!$isError) {
            $connection = mysqli_connect("localhost", $usernameDB, $passwordDB, $DATABASE);
            if (!$connection) {
                echo json_encode(array("status" => "failed-to-connect", "error" => mysqli_connect_error()));
                $isError = true;
            } else {
                $result = mysqli_query($connection, "INSERT INTO `users`(`user-id`, `user-email`, `user-firstname`, `user-lastname`, `user-password`) VALUES ('{$UID}','{$email}','{$firstname}','{$lastname}','{$password}')");
                if ($result) {
                    echo json_encode(array("status" => "insertion-success", "user_id" => $UID));
                } else {
                    echo json_encode(array("status" => "insertion-failed", "error" => mysqli_error($connection)));
                    $isError = true;
                }
            }
        }
        mysqli_close($connection);
    }
}

function tokenCheck($token)
{
    $env = parse_ini_file('.env');
    $usernameDB = $env['USERNAME'];
    $passwordDB = $env['PASSWORD'];
    $DATABASE = $env['DATABASE'];
    $result = '';
    $user = [];


    $connection = mysqli_connect("localhost", $usernameDB, $passwordDB, $DATABASE);
    if (!$connection) {
        echo json_encode(array("status" => "failed-to-connect", "error" => mysqli_connect_error()));
    } else {
        $result = mysqli_query($connection, "SELECT * FROM `token` WHERE `token-id` =" . $token . "");
        $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    if (isTokenDateValid($result[0]["creation_date"])) {
        $result = mysqli_query($connection, "SELECT * FROM `users` WHERE `user-id` =" . $result[0]["user-id"] . "");
        $result = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_close($connection);
        array_push($user, $result[0]["user-id"]);
        array_push($user, $result[0]["user-email"]);
        array_push($user, $result[0]["user-firstname"]);
        array_push($user, $result[0]["user-lastname"]);
        echo json_encode(array("status" => "valid-connection", "value" => $user));
    } else {
        echo json_encode(array("status" => "invalid-token"));
    }
}

function isTokenDateValid($date)
{
    $difference = abs(time() - strtotime($date));
    $days_difference = floor($difference / (60 * 60 * 24));
    if ($days_difference > 7) {
        return false;
    } else {
        return true;
    }
}

function tokenCreation($userid)
{
    $env = parse_ini_file('.env');
    $usernameDB = $env['USERNAME'];
    $passwordDB = $env['PASSWORD'];
    $DATABASE = $env['DATABASE'];

    $token = generateUniqueId();
    $date = date("Y-m-d");
    $connection = mysqli_connect("localhost", $usernameDB, $passwordDB, $DATABASE);
    if (!$connection) {
        echo json_encode(array("status" => "failed-to-connect", "error" => mysqli_connect_error()));
    } else {
        $result = mysqli_query($connection, "INSERT INTO `token`(`token-id`, `creation_date`, `user-id`) VALUES ('{$token}','{$date}','{$userid}')");
        echo json_encode(array("status" => "token-added", "token" => $token, "user-id" => $userid));
    }
    mysqli_close($connection);
}

function generateUniqueId()
{
    $data = PHP_MAJOR_VERSION < 7 ? openssl_random_pseudo_bytes(16) : random_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}