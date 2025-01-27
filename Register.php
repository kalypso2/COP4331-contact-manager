<?php
    $inData = getRequestInfo();

    if (!isset($inData["firstName"]) || !isset($inData["lastName"]) || !isset($inData["login"]) || !isset($inData["password"])) {
        returnWithError("Missing required fields");
        exit();
    }

    $firstName = $inData["firstName"];
    $lastName = $inData["lastName"];
    $login = $inData["login"];
    $password = $inData["password"];

    $conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");
    if ($conn->connect_error) 
    {
        returnWithError($conn->connect_error);
        exit();
    } 
    else
    {
        $stmt = $conn->prepare("SELECT ID FROM Users WHERE Login=?");
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $stmt->close();
            $conn->close();
            returnWithError("Login already exists");
            exit();
        }

        $stmt->close();

        $stmt = $conn->prepare("INSERT INTO Users (FirstName, LastName, Login, Password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $firstName, $lastName, $login, $password);

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
	    returnWithError("");
        } else {
            $stmt->close();
            $conn->close();
            returnWithError("Error registering user");
        }
    }

    function getRequestInfo()
    {
        return json_decode(file_get_contents('php://input'), true);
    }

    function sendResultInfoAsJson($obj)
    {
        header('Content-type: application/json');
        echo $obj;
    }

    function returnWithError($err)
    {
        $retValue = '{"error":"' . $err . '"}';
        sendResultInfoAsJson($retValue);
    }
?>
