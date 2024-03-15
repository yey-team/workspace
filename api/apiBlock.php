<?php
// Set headers to allow cross-origin requests and define allowed methods and headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: *');
header('Content-Type: application/json');

// Check if the required GET parameters are set for creating a new block
if (isset($_GET["createBlock"]) && isset($_GET["positionX"]) && isset($_GET["positionY"]) && isset($_GET["contentType"]) && isset($_GET["content"]) && isset($_GET["creator_id"])) {
    // Call the function to create a new block with the provided parameters
    createBlock($_GET["positionX"], $_GET["positionY"], $_GET["contentType"], $_GET["content"], $_GET["creator_id"]);
} elseif (isset($_GET["getAllBlocks"])) {
    // If the getAllBlocks parameter is set, retrieve all blocks
    getAllBlocks();
} elseif (isset($_GET["getBlock"]) && isset($_GET["blockID"])) {
    // If the getBlock parameter is set along with a specific blockID, retrieve the corresponding block
    getBlock($_GET["blockID"]);
}

// Define a function to create a new block
function createBlock($positionX, $positionY, $contentType, $content, $creator_id)
{
    // Generate a unique ID for the new block
    $UID = generateUniqueId();

    // Load database configuration from .env file
    $env = parse_ini_file('.env');
    $usernameDB = $env['USERNAME'];
    $passwordDB = $env['PASSWORD'];
    $DATABASE = $env['DATABASE'];

    // Establish a connection to the MySQL database
    $connection = mysqli_connect("localhost", $usernameDB, $passwordDB, $DATABASE);

    // Check the connection, if failed, return an error
    if (!$connection) {
        echo json_encode(array("status" => "failed-to-connect", "error" => mysqli_connect_error()));
    } else {
        // If connected, insert the new block data into the database
        $result = mysqli_query($connection, "INSERT INTO `blocks`(`id`, `positionX`, `positionY`, `contentType`, `content`, `creator_id`) VALUES ('{$UID}','{$positionX}', '{$positionY}','{$contentType}','{$content}','{$creator_id}')");
        // Check if the insertion was successful and return the result
        if ($result) {
            echo json_encode(array("status" => "insertion-success", "block_id" => $UID));
        } else {
            echo json_encode(array("status" => "insertion-failed", "error" => mysqli_error($connection)));
        }
    }
}

// Define a function to retrieve all blocks from the database
function getAllBlocks()
{
    // Load database configuration from .env file
    $env = parse_ini_file('.env');
    $usernameDB = $env['USERNAME'];
    $passwordDB = $env['PASSWORD'];
    $DATABASE = $env['DATABASE'];

    // Establish a connection to the MySQL database
    $connection = mysqli_connect("localhost", $usernameDB, $passwordDB, $DATABASE);

    // Check the connection, if failed, return an error
    if (!$connection) {
        echo json_encode(array("status" => "failed-to-connect", "error" => mysqli_connect_error()));
    } else {
        // If connected, retrieve all blocks from the database
        $result = mysqli_query($connection, "SELECT * FROM `blocks`");
        if ($result) {
            $resultArray = array();
            // Iterate through each row in the result and add it to the result array
            foreach ($result as $key => $value) {
                array_push($resultArray, $value);
            }
            // Return the retrieved blocks
            echo json_encode(array("status" => "success", "result" => $resultArray));
        } else {
            echo json_encode(array("status" => "failed", "error" => mysqli_error($connection)));
        }
    }
}

// Define a function to retrieve a specific block based on its ID
function getBlock($blockID)
{
    // Load database configuration from .env file
    $env = parse_ini_file('.env');
    $usernameDB = $env['USERNAME'];
    $passwordDB = $env['PASSWORD'];
    $DATABASE = $env['DATABASE'];

    // Establish a connection to the MySQL database
    $connection = mysqli_connect("localhost", $usernameDB, $passwordDB, $DATABASE);

    // Check the connection, if failed, return an error
    if (!$connection) {
        echo json_encode(array("status" => "failed-to-connect", "error" => mysqli_connect_error()));
    } else {
        // If connected, retrieve the specific block by its ID
        $result = mysqli_query($connection, "SELECT * FROM `blocks` WHERE `id` = $blockID;");
        if ($result) {
            $resultArray = array();
            // Iterate through each row in the result and add it to the result array
            foreach ($result as $key => $value) {
                array_push($resultArray, $value);
            }
            // Return the requested block
            echo json_encode(array("status" => "success", "result" => $resultArray));
        } else {
            echo json_encode(array("status" => "failed", "error" => mysqli_error($connection)));
        }
    }
}

// Define a function to generate a unique ID, accommodating different PHP versions
function generateUniqueId()
{
    // Generate a random byte sequence; the method depends on the PHP version
    $data = PHP_MAJOR_VERSION < 7 ? openssl_random_pseudo_bytes(8) : random_bytes(8);
    // Convert the binary data to an unsigned integer
    $id = unpack('Q', $data)[1];
    return $id;
}
