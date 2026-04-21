<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Auth controller
 *
 * Handles: login, register, logout
 *
 * Logged actions (Log #1–5):
 *   1. Successful login
 *   2. Failed login attempt
 *   3. Successful registration
 *   4. Validation failure on registration
 *   5. Logout
 */
class Auth extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }

    // ── GET /login ─────────────────────────────────────────
    public function login(): void
    {
        if ($this->is_logged_in()) {
            redirect('blog');
        }
        $this->render('auth/login', ['title' => 'Login']);
    }

    // ── POST /login ────────────────────────────────────────
    public function login_post(): void
    {
        // Validators
        $this->form_validation->set_rules('username', 'Username', 'required|min_length[3]|max_length[50]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');

        if ($this->form_validation->run() === FALSE) {
            $this->Log_model->warning('Auth', 'login_post', 'Login form validation failed');
            $this->render('auth/login', ['title' => 'Login']);
            return;
        }

        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password'); // raw – needed for verify

        $user = $this->User_model->authenticate($username, $password);

        if ($user === null) {
            // Log #2 – failed login
            $this->Log_model->warning('Auth', 'login_post',
                "Failed login attempt for username: $username", null);

            $this->session->set_flashdata('error', 'Invalid username or password.');
            redirect('login');
            return;
        }

        // Start session
        $this->session->set_userdata([
            'user_id'  => $user['id'],
            'username' => $user['username'],
            'role'     => $user['role'],
        ]);

        // Log #1 – successful login
        $this->Log_model->info('Auth', 'login_post',
            "User '{$user['username']}' logged in successfully", (int)$user['id']);

        redirect('blog');
    }

    // ── GET /register ──────────────────────────────────────
    public function register(): void
    {
        if ($this->is_logged_in()) redirect('blog');
        $this->render('auth/register', ['title' => 'Register']);
    }

    // ── POST /register ─────────────────────────────────────
    public function register_post(): void
    {
        // 8 backend validators ─────────────────────────────
        $this->form_validation->set_rules(
            'username', 'Username',
            'required|min_length[3]|max_length[50]|alpha_numeric|is_unique[users.username]',
            [
                'is_unique'     => 'This username is already taken.',
                'alpha_numeric' => 'Username may only contain letters and numbers.',
            ]
        );
        $this->form_validation->set_rules(
            'email', 'Email',
            'required|valid_email|is_unique[users.email]',
            ['is_unique' => 'This email is already registered.']
        );
        $this->form_validation->set_rules(
            'password', 'Password',
            'required|min_length[8]|regex_match[/^(?=.*[A-Z])(?=.*\d).+$/]',
            ['regex_match' => 'Password must contain at least one uppercase letter and one digit.']
        );
        $this->form_validation->set_rules(
            'password_confirm', 'Password Confirmation',
            'required|matches[password]'
        );

        if ($this->form_validation->run() === FALSE) {
            // Log #4 – validation failure
            $this->Log_model->warning('Auth', 'register_post',
                'Registration form validation failed for: ' . $this->input->post('username', TRUE));

            $this->render('auth/register', ['title' => 'Register']);
            return;
        }

        $id = $this->User_model->create([
            'username' => $this->input->post('username', TRUE),
            'email'    => $this->input->post('email',    TRUE),
            'password' => $this->input->post('password'),
            'role'     => 'editor',
        ]);

        // Log #3 – successful registration
        $this->Log_model->info('Auth', 'register_post',
            "New user registered: " . $this->input->post('username', TRUE), $id);

        $this->session->set_flashdata('success', 'Account created! Please log in.');
        redirect('login');
    }

    // ── GET /logout ────────────────────────────────────────
    public function logout(): void
    {
        $username = $this->session->userdata('username');

        // Log #5 – logout
        $this->Log_model->info('Auth', 'logout', "User '$username' logged out");

        $this->session->sess_destroy();
        $this->session->set_flashdata('success', 'You have been logged out.');
        redirect('login');
    }
}
