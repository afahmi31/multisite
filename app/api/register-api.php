<?php

function mcd_register_user(WP_REST_Request $request) {
    $nonce = $request->get_header('X-WP-Nonce');
    if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
        mst_rest_activity(
            [
                'method'   => 'POST',
                'endpoint' => '/custom/v1/register',
                'message'  => "nonce invalid"
            ]
        );
        return mst_api_error('Nonce is invalid or has expired.', 403);
    }

    try {

        // validation
        $data = $request->get_json_params();
        $errors = mst_validate_user($data);
        if ($errors) {
            return mst_api_error('Validation failed.',422, $errors);
        }


        $first_name = sanitize_text_field($request['first_name']);
        $last_name  = sanitize_text_field($request['last_name']);
        $email      = sanitize_email($request['email']);
        $password   = $request['password'];


        $username = $email;

        if (username_exists($username)) {
            mst_rest_activity(
                [
                    'method'   => 'POST',
                    'endpoint' => '/custom/v1/register',
                    'message'  => "username exists"
                ]
            );
            return mst_api_error('Username already exists.',400);
        }

        $user_id = wp_create_user($username, $password, $email);

        if (is_wp_error($user_id)) {
            mst_rest_activity(
                [
                    'method'   => 'POST',
                    'endpoint' => '/custom/v1/register',
                    'message'  =>  $user_id->get_error_message()
                ]
            );
            return mst_api_error($user_id->get_error_message(), 400);
        }

        wp_update_user([
            'ID' => $user_id,
            'first_name' => $first_name,
            'last_name'  => $last_name,
        ]);

        
        $user = new WP_User($user_id);
        $user->set_role('member');

        update_field('mst_verified', false, 'user_'.$user_id);
        $token = mst_generate_verification_token($user_id);
        mst_send_verification_email($user_id, $email, $token);

        mst_rest_activity(
            [
                'method'   => 'POST',
                'endpoint' => '/custom/v1/register',
                'message'  => "Success register email : " . $email
            ]
        );
        wp_send_json_success([
            'message' => 'Registrasi berhasil.',
            'user_id' => $user_id
        ]);
        return mst_api_success($user, 'Registration success', 200);
    } catch (Throwable $e) {
        mst_rest_activity(
            [
                'method'   => 'POST',
                'endpoint' => '/custom/v1/register',
                'message'  => "Error: " . $e->getMessage()
            ]
        );
        return mst_api_error($e->getMessage());
    }
}

function mst_validate_user($data, $update = false, $id = null) {
    $errors = [];
    if (empty($data['first_name'])) $errors['first_name'] = 'First Name is required.';
    if (empty($data['last_name'])) $errors['last_name'] = 'Last Name is required.';
    if (empty($data['last_name'])) $errors['last_name'] = 'Last Name is required.';
    if (empty($data['password'])) {
        $errors['password'] = 'Password is required.';
    } elseif (strlen($data['password']) < 6) {
        $errors['password'] = 'Password must be at least 6 characters.';
    } // You can add more password validation rules here, like checking for special characters, etc.
    elseif (!preg_match('/[A-Z]/', $data['password'])) {
        $errors['password'] = 'Password must contain at least one uppercase letter.';
    } elseif (!preg_match('/[a-z]/', $data['password'])) {
        $errors['password'] = 'Password must contain at least one lowercase letter.';
    } elseif (!preg_match('/[0-9]/', $data['password'])) {
        $errors['password'] = 'Password must contain at least one number.';
    } // Add more rules as needed for

    if (empty($data['email'])) {
        $errors['email'] = 'Email is required.';
    } elseif (!is_email($data['email'])) {
        $errors['email'] = 'Email is invalid.';
    
    } 
    else {
        $user = get_user_by('email', $data['email']);
        if ($user && (!$update || $user->ID != $id)) {
            $errors['email'] = 'Email already exists.';
        }
    }

    if (empty($data['terms'])) {
    $errors['terms'] = 'You must agree to the terms.';
    }
    return $errors;
}

function mst_generate_verification_token($user_id) {
    $token = wp_generate_password(32, false);
    update_field('mst_email_verification_token', $token, 'user_'.$user_id);
    return $token;
}

function mst_send_verification_email($user_id, $email, $token) {
    $verification_link = site_url('/') . '?action=verify_email&user=' . $user_id .  '&email=' .$email .  '&token=' .$token;

    $subject = 'Please Verify Your Email Address';

    $message = sprintf(
        "Hello,\n\nThank you for registering with us!\n\nTo complete your registration and activate your account, please verify your email address by clicking the link below:\n\n%s\n\nIf you did not create an account, you can safely ignore this email.\n\nBest regards,\n%s Team",
        $verification_link,
        get_bloginfo('name')
    );

    $headers = ['Content-Type: text/plain; charset=UTF-8'];

    wp_mail($email, $subject, $message, $headers);
}

add_action('wp_ajax_check_email_availability', 'check_email_availability');
add_action('wp_ajax_nopriv_check_email_availability', 'check_email_availability');

function check_email_availability() {
    if (!isset($_GET['email']) || empty($_GET['email'])) {
        wp_send_json([
            'valid' => false,
            'available' => false,
            'message' => 'Email is required.'
        ]);
    }

    $email = sanitize_email($_GET['email']);

    if (!is_email($email)) {
        wp_send_json([
            'valid' => false,
            'available' => false,
            'message' => 'Invalid email format.'
        ]);
    }

    $user = get_user_by('email', $email);

    if ($user) {
        wp_send_json([
            'valid' => true,
            'available' => false,
            'message' => 'Email is already registered.'
        ]);
    } else {
        wp_send_json([
            'valid' => true,
            'available' => true,
            'message' => 'Email is available.'
        ]);
    }
}