<?php
/**
 * Created by PhpStorm.
 * User: Simona
 * Date: 28/02/2019
 * Time: 16:39
 */
header("Content-Type: application/json; charset=UTF-8");
$start = microtime(true);
function init(){
    require_once('Users.php');
    require_once('Logger.php');
    require_once('Upload.php');

}

function runListUsers(){

    $user   = new Users();
    $response = $user->getUsersList();
    return $response;
}

function runUpload($file){
    $file = realpath($file);
    $upload   = new Upload($file);
    $response = $upload->uploadBlob();
    return $response;

}

function codeToMessage($code)
{
    switch ($code) {
        case UPLOAD_ERR_INI_SIZE:
            $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
            break;
        case UPLOAD_ERR_FORM_SIZE:
            $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
            break;
        case UPLOAD_ERR_PARTIAL:
            $message = "The uploaded file was only partially uploaded";
            break;
        case UPLOAD_ERR_NO_FILE:
            $message = "No file was uploaded";
            break;
        case UPLOAD_ERR_NO_TMP_DIR:
            $message = "Missing a temporary folder";
            break;
        case UPLOAD_ERR_CANT_WRITE:
            $message = "Failed to write file to disk";
            break;
        case UPLOAD_ERR_EXTENSION:
            $message = "File upload stopped by extension";
            break;

        default:
            $message = "Unknown upload error";
            break;
    }
    return $message;
}

init();
$response = [];
$responseCode = 200;


switch ($_SERVER['REQUEST_METHOD']){
    case 'POST':
        if(!count($_FILES)){
            $response = ['error' => "Empty file"];
            break;
        }

        if(isset($_FILES['file']['error']) && $_FILES['file']['error'] >0){
            $message = codeToMessage($_FILES['file']['error']);
            $response = ['error' => $message];
            break;
        }
        if(isset($_FILES['file']['tmp_name'])){
            $finalTargetDir = strpos($_SERVER['DOCUMENT_ROOT'], 'wamp64') === FALSE ?  $_SERVER['DOCUMENT_ROOT'] . '\tmp' :  $_SERVER['DOCUMENT_ROOT'] . '/m_azure/azure_app/tmp';
            $separator = strpos($_SERVER['DOCUMENT_ROOT'], 'wamp64') === FALSE ? '\\' : "/";
            $target_file = $finalTargetDir  . $separator . basename($_FILES["file"]["name"]);
            $uploadResult = move_uploaded_file($_FILES['file']['tmp_name'], $target_file);
            if(!$uploadResult){
                $response = ['error' => "An error occurred uploading the file"];
                break;
            }
            $uploadedFile = pathinfo($target_file, PATHINFO_DIRNAME) . $separator . pathinfo($target_file,PATHINFO_BASENAME );
            $result = ['file_uploaded' => $target_file . pathinfo($target_file,PATHINFO_BASENAME )];
            $response = runUpload($uploadedFile);
            unlink($target_file);
        }
        break;
    case 'GET':
        $response = runListUsers();
        break;
    default:
        $response = ['error' => 'Unrecognized request type'];
}

if(isset($response['error'])){
    $responseCode = 400;
}

$time_elapsed_secs = microtime(true) - $start;

$logger = new Logger();
$logger->log(
    [
        'method' => $_SERVER['REQUEST_METHOD'],
        'url' => "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]",
        'postBody' => json_decode(file_get_contents('php://input'), true),
        'response_code'=> $responseCode,
        'response'=> $response,
        'time'=> $time_elapsed_secs,
    ]
);
http_response_code($responseCode);

// show users data in json format
echo json_encode($response);