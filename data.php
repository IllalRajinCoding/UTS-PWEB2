<?php
// Dummy data for testing purposes
$users = [
    [
        'id' => 1,
        'name' => 'John Doe',
        'email' => 'john.doe@example.com',
        'role' => 'admin'
    ],
    [
        'id' => 2,
        'name' => 'Jane Smith',
        'email' => 'jane.smith@example.com',
        'role' => 'editor'
    ],
    [
        'id' => 3,
        'name' => 'Alice Johnson',
        'email' => 'alice.johnson@example.com',
        'role' => 'subscriber'
    ]
];

// Example usage
foreach ($users as $user) {
    echo "ID: {$user['id']}, Name: {$user['name']}, Email: {$user['email']}, Role: {$user['role']}<br>";
}
