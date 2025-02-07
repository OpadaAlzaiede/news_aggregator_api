<?php

return [

    'auth' => [
        'register_success' => 'Registered successfully.',
        'invalid_credentials' => 'The provided credentials don\'t match our records.',
        'login_success' => 'You have logged in successfully.',
        'logout_success' => 'You have logged out successfully.',
        'reset_link_sent' => 'A reset link has been sent to your email.',
        'invalid_or_expired_token' => 'Reset link is invalid or has expired.',
        'user_does_not_exist' => 'User doesn\'t exist.',
        'invalid_email' => 'Invalid email.',
        'password_update_success' => 'Your password has been updated successfully.',
        'email_already_verified' => 'Email has already been verified.',
        'email_verified' => 'Email verified successfully.',
        'email_verify' > 'Please verify your email.',
    ],
    'general' => [
        'preferences_not_set' => 'user doesn\'t have preferences.',
        'request_with_json' => 'Please request with HTTP header: Accept: application/json',
    ],

    'preferences' => [
        'preferences_set_successfully' => 'Your preferences have been set successfully.',
        'preferences_set_failed' => 'An error occurred while setting preferences.',
        'preferences_deleted_successfully' => 'Your preferences have been deleted successfully.',
        'preferences_deletion_failed' => 'An error occurred while deleting preferences.',
    ],

    'errors' => [
        '404' => 'record not found.',
        '429' => 'too many requests.',
        '401' => 'unauthorized.',
    ],
];
