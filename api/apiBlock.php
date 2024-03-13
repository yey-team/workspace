<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: *');
header('Content-Type: application/json');


if (isset($_GET["createBlock"]) && isset($_GET["positionX"]) && isset($_GET["positionY"]) && isset($_GET["contentType"]) && isset($_GET["content"]) && isset($_GET["creator_id"])) {
    createBlock($_GET["positonX"], $_GET["positonY"], $_GET["contentType"], $_GET["content"], $_GET["creator_id"]);
} elseif (isset($_GET["getAllBlocks"])) {
    getAllBlocks();
}

function createBlock($positionX, $positionY, $contentType, $content, $creator_id)
{
    $UID = generateUniqueId();

    $env = parse_ini_file('.env');
    $usernameDB = $env['USERNAME'];
    $passwordDB = $env['PASSWORD'];
    $DATABASE = $env['DATABASE'];

    $connection = mysqli_connect("localhost", $usernameDB, $passwordDB, $DATABASE);

    if (!$connection) {
        echo json_encode(array("status" => "failed-to-connect", "error" => mysqli_connect_error()));
    } else {
        $result = mysqli_query($connection, "INSERT INTO `blocks`(`id`, `positionX`, `positionY`, `contentType`, `content`, `creator_id`) VALUES ('{$UID}','{$positionX}', '{$positionY}','{$contentType}','{$content}','{$creator_id}')");
        if ($result) {
            echo json_encode(array("status" => "insertion-success", "block_id" => $UID));
        } else {
            echo json_encode(array("status" => "insertion-failed", "error" => mysqli_error($connection)));
        }
    }
}


function getAllBlocks()
{
    $env = parse_ini_file('.env');
    $usernameDB = $env['USERNAME'];
    $passwordDB = $env['PASSWORD'];
    $DATABASE = $env['DATABASE'];

    $connection = mysqli_connect("localhost", $usernameDB, $passwordDB, $DATABASE);

    if (!$connection) {
        echo json_encode(array("status" => "failed-to-connect", "error" => mysqli_connect_error()));
    } else {
        $result = mysqli_query($connection, "SELECT * FROM `blocks`");
        if ($result) {
            $resultArray = array();
            foreach ($result as $key => $value) {
                array_push($resultArray, $value);
            }
            echo json_encode(array("status" => "successs", "result" => $resultArray));
        } else {
            echo json_encode(array("status" => "failed", "error" => mysqli_error($connection)));
        }
    }
}

function generateUniqueId()
{
    $data = PHP_MAJOR_VERSION < 7 ? openssl_random_pseudo_bytes(8) : random_bytes(8);
    $id = unpack('Q', $data)[1];
    return $id;
}