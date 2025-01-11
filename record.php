<?php

// Check if the "data" directory does not exist
if (!file_exists("data")) {
	// Create the "data" directory with permissions 0775 (read, write, execute for owner/group, read/execute for others)
	mkdir("data", 0775);
	chmod("data", 0775);
}

// Get the raw POST data from the input stream
$payload = file_get_contents("php://input");
// Decode the JSON payload into a PHP associative array
$json = json_decode($payload, true);

// Extract the "topic" field from the decoded JSON
$topic = $json["topic"];
// Split the topic into parts using "/" as the delimiter
$parts = explode("/", $topic);
// Get the device ID from the third part of the topic
$deviceID = $parts[2];

// Format the JSON data into a pretty-printed string
$formatted = json_encode($json, JSON_PRETTY_PRINT);

// Get the "_type" field from the decoded JSON
$type = $json["_type"];

// Save the formatted JSON to a file with a timestamp, device ID, and type as the filename
file_put_contents("data/".date("YmdHis")."-".$deviceID.".".$type.".json", $formatted);
// Save the formatted JSON to a "last" file for the specific device ID and type
file_put_contents("data/last-".$deviceID.".".$type.".json", $formatted);



// ******************************************************************************
// Prepeare the response
// ******************************************************************************

// Define an associative array mapping IDs to base64-encoded PNG strings for faces
$faces = [
    "nx4" => "base64PNGCode",
];

// Define an associative array mapping IDs to readable names
$names = [
    "nx4" => "readable Name"
];

// Initialize an empty array to store responses
$responses = [];

// Get all files matching the pattern "data/last-*.location.json"
$lasts = glob("data/last-*.location.json");

// Iterate over each file in the "lasts" array
foreach ($lasts as $last) {
    // Decode the JSON content of the file into a PHP associative array
    $data = json_decode(file_get_contents($last), true);

    // If the "tid" (tracker ID) does not exist in the names array, add it with the tracker ID as the name
    if (!isset($names[$data["tid"]])) {
        $names[$data["tid"]] = $data["tid"];
    }

    // If the "tid" does not exist in the faces array, add it with an empty string as the face
    if (!isset($faces[$data["tid"]])) {
        $faces[$data["tid"]] = "";
    }

    // Add a "card" type response to the responses array with tracker details
    $responses[] = [
        "_type" => "card",
        "tid"   => $data["tid"],
        "name"  => $names[$data["tid"]],
        "face"  => $faces[$data["tid"]],
    ];

    // Add a "location" type response to the responses array with location details
    $responses[] = [
        '_type' => 'location',
        'tid'   => $data["tid"],
        'lat'   => $data["lat"],
        'lon'   => $data["lon"],
        'tst'   => $data["tst"], // Timestamp of the location
        'acc'   => $data["acc"], // Accuracy of the location data
    ];
}

// Set the Content-Type header to indicate JSON response
header('Content-Type: application/json');

// Encode the responses array into a JSON string
$res = json_encode($responses);

// Save the JSON response to a file named with the device ID
file_put_contents("data/lastresponse-".$deviceID.".json", $res);

// Output the JSON response
echo $res;

?>
