<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!file_exists('assets/img')) {
        mkdir('assets/img', 0777, true);
    }

    $data = json_decode(file_get_contents('php://input'), true);
    $imageData = $data['image'];

    $imageData = explode(',', $imageData)[1];
    $imageData = base64_decode($imageData);

    $filename = 'assets/img/face_' . time() . '.jpg';
    file_put_contents($filename, $imageData);

    $apiUrl = 'https://4138-34-124-139-74.ngrok-free.app/FaceDetection';
    $cfile = curl_file_create($filename, 'image/jpeg', basename($filename));

    $postData = ['imagefile' => $cfile];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    // Decode the API response and extract only `predicted_class`
    $api_response = json_decode($response, true);
    $predicted_class = $api_response['results'][0]['predicted_class'];

    // Return the `predicted_class` and image file path
    echo json_encode([
        'status' => 'success',
        'predicted_class' => $predicted_class,
        'image_path' => $filename // Add file path to the response
    ]);
}
?>
