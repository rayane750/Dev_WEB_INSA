<?php
// start session
session_start();

// include database and object files
include_once '../config/database.php';
include_once '../objects/doctor.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare doctor object
$doctor = new Doctor($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// set doctor property values
$doctor->email = $_POST['email'];
$doctor->password = base64_encode($_POST['password']);

// check if email exists
if($doctor->emailExists()){
    // check if password is correct
    if($doctor->checkPassword()){
        // set session variables
        // create success response
        $response = array(
            "status" => true,
            "message" => "Login successful!",
            "data" => array(
                "id" => $doctor->id,
                "name" => $doctor->name,
                "email" => $doctor->email,
                "phone" => $doctor->phone,
                "gender" => $doctor->gender,
                "specialist" => $doctor->specialist
            )
        );
        $_SESSION['doctor_id'] = $doctor->id;
        $_SESSION['doctor_email'] = $doctor->email;
        $_SESSION['doctor_name'] = $doctor->name;
        $_SESSION['doctor_gender'] = $doctor->gender;
        echo json_encode($response);
        //echo json_encode($doctor->name);
        // echo json_encode($doctor->checkPassword());
        // echo json_encode("");

    }
    else{
        // create error response for incorrect password
        $response = array(
            "status" => false,
            "message" => "Incorrect password!"
        );
        echo json_encode($response);
        // echo json_encode($doctor->checkPassword());
        // echo json_encode("test2");

    }
}
else{
    // create error response for non-existent email
    $response = array(
        "status" => false,
        "message" => "Email does not exist!"
    );
    echo json_encode($response);
    // echo json_encode($doctor->checkPassword());
    // echo json_encode("test3");

}

?>

