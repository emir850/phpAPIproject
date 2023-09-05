<?php
include('php\lib/simple_html_dom.php');
$channel = curl_init();

$aurl = "https://demo.flexmanager.com/v3/api/incidents/";

$headers = array(
    'api-key: SzVlZGUwYzdjMTg1Y2M4LjM2NTM5MzYw',
    'Content-Type: application/json'
);

// Construct the JSON request body
$requestBody = json_encode(array(
    'view' => 'detailed',
    'start' => 1420070400, // Start date in epoch timestamp
    'end' => 1683890769 // End date in epoch timestamp
));

curl_setopt($channel, CURLOPT_URL, $aurl);
curl_setopt($channel, CURLOPT_HTTPHEADER, $headers);
curl_setopt($channel, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($channel, CURLOPT_RETURNTRANSFER, true);
curl_setopt($channel, CURLOPT_FOLLOWLOCATION, true);

// Set the request method to POST
curl_setopt($channel, CURLOPT_POST, true);

// Set the request body
curl_setopt($channel, CURLOPT_POSTFIELDS, $requestBody);

$response = curl_exec($channel);

if (curl_errno($channel)) {
    echo 'Error Detected: ' . curl_error($channel);
} else {
    //echo $response;
}

$response = json_decode($response, true);

if (isset($response['results']) && is_array($response['results'])) {
    // Display data in a list view (HTML table)
    echo '<h1>Incidents List</h1>';
    echo '<table border="1">';
    echo '<tr><th>Incident Number</th><th>Date</th><th>Project</th><th>Type</th></tr>';

    foreach ($response['results'] as $incident) {
        echo '<tr>';
        echo '<td>' . $incident['number'] . '</td>';
        echo '<td>' . date('Y-m-d H:i:s', $incident['incidentdate']) . '</td>';
        echo '<td>' . $incident['project'] . '</td>';
        echo '<td>' . $incident['type'] . '</td>';
        echo '</tr>';
    }

    echo '</table>';
} else {
    echo 'No data found.';
}

if (file_exists('lib/simple_html_dom.php')) {
    echo 'Library file exists!';
} else {
    echo 'Library file not found.';
}

$response = str_get_html($response);
 
$table = $response->find('table' ,0);

$hdrs =[];
$dataa =[];

$headerRow = $table->find('tr',0);
foreach ($headerRow = $table->find('th') as $headerCell) {
    $hdrs[] = trim($headerCell->plaintext);
}

foreach ($table->find('tr') as $rowIndex => $row) {
    // Skip the first row (header row)
    if ($rowIndex === 0) {
        continue;
    }
    $rowData = [];
    foreach ($row->find('td') as $cell) {
        $rowData[] = trim($cell->plaintext);
    }

    $dataa[] = $rowData;
}

echo '<h1>Incidents List</h1>';
echo '<ul>';

foreach ($dataa as $row) {
    echo '<li>';
    foreach ($hdrs as $index => $header) {
        echo '<strong>' . $header . ':</strong> ' . $row[$index] . '<br>';
    }
    echo '</li>';
}

echo '</ul>';

curl_close($channel);
?>

