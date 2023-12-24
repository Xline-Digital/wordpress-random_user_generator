<?php


/*
Plugin Name: Bulk User Generator
Description: Generate multiple user accounts with random names, passwords, and a shared email address.
Version: 1.0
Author: Pasindu Dewviman 
Author URI: http://itspasindu.com
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
*/




// Function to generate a random username
function generate_random_username() {
    $prefix = 'user';
    $suffix = rand(1000, 9999);
    return $prefix . $suffix;
}

// Function to generate a random password
function generate_random_password() {
    return wp_generate_password();
}

// Function to generate a random email address
function generate_random_email() {
    $prefix = 'bulkuser';
    $suffix = rand(1000, 9999) . '@example.com';
    return $prefix . $suffix;
}

// Function to create a new user
function create_new_user($role) {
    $username = generate_random_username();
    $password = generate_random_password();
    $email = generate_random_email();

    $userdata = array(
        'user_login' => $username,
        'user_pass'  => $password,
        'user_email' => $email,
        'role'       => $role, // Set the user role
    );

    $user_id = wp_insert_user($userdata);

    if (!is_wp_error($user_id)) {
        // User created successfully
        echo "User created: $username, $password, $email, Role: $role<br>";
    } else {
        // Error creating user
        echo "Error creating user: " . $user_id->get_error_message() . "<br>";
    }
}

// Function to generate multiple users
function generate_users($count, $role) {
    for ($i = 0; $i < $count; $i++) {
        create_new_user($role);
    }
}

// Admin menu for the plugin
function bulk_user_generator_menu() {
    add_menu_page(
        'Bulk User Generator',
        'Bulk User Generator',
        'manage_options',
        'bulk_user_generator',
        'bulk_user_generator_page'
    );
}

// Page content for the admin menu
function bulk_user_generator_page() {
    echo '<div class="wrap">';
    echo '<h2>Bulk User Generator</h2>';

    // Check if the form is submitted
    if (isset($_POST['generate_users'])) {
        $user_count = absint($_POST['user_count']);
        $selected_role = sanitize_text_field($_POST['user_role']);
        generate_users($user_count, $selected_role);
    }

    // Display the form
    echo '<form method="post" action="">';
    echo '<label for="user_count">Number of Users:</label>';
    echo '<input type="number" name="user_count" min="1" value="5">';
    
    // Display the user role dropdown
    echo '<label for="user_role">Select User Role:</label>';
    echo '<select name="user_role">';
    // Add your desired user roles to the dropdown
    echo '<option value="subscriber">Subscriber</option>';
    echo '<option value="contributor">Contributor</option>';
    echo '<option value="author">Author</option>';
    echo '<option value="editor">Editor</option>';
    echo '</select>';

    echo '<input type="submit" name="generate_users" class="button button-primary" value="Generate Users">';
    echo '</form>';

    // Get Support Button
    echo '<p>For support, please <a href="https://itspasindu.com/contact-me/" target="_blank">Submit Form</a>.</p>';

    echo '</div>';
}

// Hook to add the admin menu
add_action('admin_menu', 'bulk_user_generator_menu');