<?php
/**
 * Test script for overlapping booking validation
 * This demonstrates how the API handles overlapping bookings
 */

// Test data for overlapping bookings
$testData = [
    'booking1' => [
        'resource_id' => 1,
        'start_time' => '2024-01-15 10:00:00',
        'end_time' => '2024-01-15 12:00:00'
    ],
    'booking2' => [
        'resource_id' => 1,
        'start_time' => '2024-01-15 11:00:00', // Overlaps with booking1
        'end_time' => '2024-01-15 13:00:00'
    ],
    'booking3' => [
        'resource_id' => 1,
        'start_time' => '2024-01-15 14:00:00', // No overlap
        'end_time' => '2024-01-15 16:00:00'
    ]
];

echo "=== Overlapping Booking Validation Test ===\n\n";

echo "Test Scenario:\n";
echo "1. Create booking1 (10:00-12:00)\n";
echo "2. Approve booking1\n";
echo "3. Try to approve booking2 (11:00-13:00) - should fail due to overlap\n";
echo "4. Try to approve booking3 (14:00-16:00) - should succeed (no overlap)\n\n";

echo "API Endpoints:\n";
echo "- POST /api/bookings - Create booking\n";
echo "- POST /api/bookings/{id}/approve - Approve booking (with overlap check)\n";
echo "- GET /api/bookings - List all bookings\n\n";

echo "Expected JSON Responses:\n\n";

echo "1. Creating booking1:\n";
echo json_encode([
    'message' => 'Booking created successfully',
    'booking' => [
        'id' => 1,
        'resource_id' => 1,
        'start_time' => '2024-01-15 10:00:00',
        'end_time' => '2024-01-15 12:00:00',
        'booking_status_id' => 1, // pending
        'status' => ['status' => 'pending']
    ]
], JSON_PRETTY_PRINT);

echo "\n\n2. Approving booking1:\n";
echo json_encode([
    'message' => 'Booking approved'
], JSON_PRETTY_PRINT);

echo "\n\n3. Trying to approve overlapping booking2 (should fail):\n";
echo json_encode([
    'error' => 'Booking cannot be approved due to overlapping with existing approved bookings',
    'message' => 'There is already an approved booking for this resource during the requested time period',
    'overlapping_bookings' => [
        [
            'id' => 1,
            'user' => 'John Doe',
            'resource' => 'Conference Room A',
            'start_time' => '2024-01-15 10:00:00',
            'end_time' => '2024-01-15 12:00:00'
        ]
    ]
], JSON_PRETTY_PRINT);

echo "\n\n4. Approving non-overlapping booking3:\n";
echo json_encode([
    'message' => 'Booking approved'
], JSON_PRETTY_PRINT);

echo "\n\n=== Implementation Details ===\n";
echo "- Overlap check is performed in the approve() method\n";
echo "- Only approved bookings (status_id = 2) are checked for overlaps\n";
echo "- Pending and rejected bookings don't block approvals\n";
echo "- HTTP 409 Conflict status is returned for overlapping bookings\n";
echo "- Detailed overlap information is provided in the response\n"; 