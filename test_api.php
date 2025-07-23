<?php

// Simple API test script
// Run this with: php test_api.php

$baseUrl = 'http://localhost:8000/api';

echo "=== Facility Booking API Test ===\n\n";

// Test 1: Register a user
echo "1. Testing user registration...\n";
$registerData = [
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => 'password123',
    'password_confirmation' => 'password123',
    'role' => 'user'
];

$response = makeRequest('POST', '/register', $registerData);
if (isset($response['token'])) {
    echo "✓ Registration successful\n";
    $token = $response['token'];
} else {
    echo "✗ Registration failed: " . json_encode($response) . "\n";
    exit;
}

// Test 2: Login
echo "\n2. Testing login...\n";
$loginData = [
    'email' => 'test@example.com',
    'password' => 'password123'
];

$response = makeRequest('POST', '/login', $loginData);
if (isset($response['token'])) {
    echo "✓ Login successful\n";
    $token = $response['token'];
} else {
    echo "✗ Login failed: " . json_encode($response) . "\n";
    exit;
}

// Test 3: Get current user
echo "\n3. Testing get current user...\n";
$response = makeRequest('GET', '/user', null, $token);
if (isset($response['id'])) {
    echo "✓ Get user successful\n";
} else {
    echo "✗ Get user failed: " . json_encode($response) . "\n";
}

// Test 4: Get resources
echo "\n4. Testing get resources...\n";
$response = makeRequest('GET', '/resources', null, $token);
if (is_array($response)) {
    echo "✓ Get resources successful (found " . count($response) . " resources)\n";
} else {
    echo "✗ Get resources failed: " . json_encode($response) . "\n";
}

// Test 5: Create a booking (if resources exist)
echo "\n5. Testing create booking...\n";
if (is_array($response) && count($response) > 0) {
    $resourceId = $response[0]['id'];
    $bookingData = [
        'resource_id' => $resourceId,
        'start_time' => date('Y-m-d H:i:s', strtotime('+1 hour')),
        'end_time' => date('Y-m-d H:i:s', strtotime('+2 hours'))
    ];
    
    $response = makeRequest('POST', '/bookings', $bookingData, $token);
    if (isset($response['message'])) {
        echo "✓ Create booking successful\n";
    } else {
        echo "✗ Create booking failed: " . json_encode($response) . "\n";
    }
} else {
    echo "⚠ No resources available for booking test\n";
}

// Test 6: Get my bookings
echo "\n6. Testing get my bookings...\n";
$response = makeRequest('GET', '/my-bookings', null, $token);
if (is_array($response)) {
    echo "✓ Get my bookings successful (found " . count($response) . " bookings)\n";
} else {
    echo "✗ Get my bookings failed: " . json_encode($response) . "\n";
}

// Test 7: Logout
echo "\n7. Testing logout...\n";
$response = makeRequest('POST', '/logout', null, $token);
if (isset($response['message'])) {
    echo "✓ Logout successful\n";
} else {
    echo "✗ Logout failed: " . json_encode($response) . "\n";
}

echo "\n=== Test completed ===\n";

function makeRequest($method, $endpoint, $data = null, $token = null) {
    global $baseUrl;
    
    $url = $baseUrl . $endpoint;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    
    $headers = ['Content-Type: application/json'];
    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    if ($data && in_array($method, ['POST', 'PUT', 'PATCH'])) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $decoded = json_decode($response, true);
    
    if ($httpCode >= 400) {
        return $decoded ?: ['error' => 'HTTP ' . $httpCode];
    }
    
    return $decoded ?: $response;
} 