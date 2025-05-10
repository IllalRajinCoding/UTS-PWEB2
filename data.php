<?php
// Dummy data for testing purposes
$users = [
    [
        'id' => 1,
        'name' => 'Hilal',
        'email' => 'hilal.doe@google.com',
        'role' => 'admin'
    ],
    [
        'id' => 2,
        'name' => 'Ferdiansyah',
        'email' => 'Diferh@Dihlearn.com',
        'role' => 'editor'
    ],
    [
        'id' => 3,
        'name' => 'Alice Johnson',
        'email' => 'alice.johnson@example.com',
        'role' => 'Investor'
    ]
];

// Example usage
foreach ($users as $user) {
    echo "ID: {$user['id']}, Name: {$user['name']}, Email: {$user['email']}, Role: {$user['role']}<br>";
}
