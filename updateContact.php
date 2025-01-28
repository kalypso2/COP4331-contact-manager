<?php
    $inData = getRequestInfo();

    $id	= $inData["id"]; 
    $firstName = $inData["firstName"];
    $lastName = $inData["lastName"];
    $phone = $inData["phone"];
    $email = $inData["email"];

    $conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");

    if ($conn->connect_error)
    {
	  returnWithError($conn->connect_error);
    }
    else
    {
        $stmt = $conn->prepare("UPDATE Contacts SET FirstName = ?, LastName = ?, Phone = ?, Email = ? WHERE ID = ?");
        $stmt->bind_param("ssssi", $firstName, $lastName, $phone, $email, $id);

        $stmt->execute();

        if ($stmt->affected_rows > 0) {

            returnWithError("");
        } else {

            returnWithError("no changes were made");
        }

        $stmt->close();
        $conn->close();
    }

    function getRequestInfo() {
        return json_decode(file_get_contents('php://input'), true);
    }

    function sendResultInfoAsJson($obj) {
        header('Content-type: application/json');
        echo $obj;
    }

    function returnWithError($err) {
        $retValue = '{"error":"' . $err . '"}';
        sendResultInfoAsJson($retValue);
    }
?>