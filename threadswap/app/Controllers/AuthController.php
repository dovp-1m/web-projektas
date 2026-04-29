<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\LogModel;

class AuthController extends BaseController
{
    protected UserModel $userModel;
    protected LogModel  $logModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->logModel  = new LogModel();
    }

    // ── Register GET ──────────────────────────────────────────────
    public function register(): string
    {
        if (session()->has('user')) {
            return redirect()->to(base_url())->getBody();
        }
        return view('auth/register', ['title' => 'Sign Up']);
    }

    // ── Register POST ─────────────────────────────────────────────
    public function registerPost()
    {
        $rules = [
            'username'         => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'email'            => 'required|valid_email|is_unique[users.email]',
            'password'         => 'required|min_length[8]|max_length[72]',
            'password_confirm' => 'required|matches[password]',
            'first_name'       => 'required|min_length[2]|max_length[50]|alpha',
            'last_name'        => 'required|min_length[2]|max_length[50]|alpha',
        ];

        $messages = [
            'username'         => ['is_unique'   => 'This username is already taken.'],
            'email'            => ['is_unique'   => 'This email address is already registered.',
                                   'valid_email' => 'Please enter a valid email address.'],
            'password'         => ['min_length'  => 'Password must be at least 8 characters.'],
            'password_confirm' => ['matches'     => 'Passwords do not match.'],
            'first_name'       => ['alpha'       => 'First name must contain letters only.'],
            'last_name'        => ['alpha'       => 'Last name must contain letters only.'],
        ];

        if (!$this->validate($rules, $messages)) {
            return view('auth/register', [
                'title'      => 'Sign Up',
                'validation' => $this->validator,
                'old'        => $this->request->getPost(),
            ]);
        }

        $userId = $this->userModel->insert([
            'username'   => $this->request->getPost('username'),
            'email'      => $this->request->getPost('email'),
            'password'   => $this->request->getPost('password'),
            'first_name' => $this->request->getPost('first_name'),
            'last_name'  => $this->request->getPost('last_name'),
        ]);

        $this->logModel->log(
            'INFO', 'AuthController', 'registerPost',
            'New user registered: ' . $this->request->getPost('email'),
            $userId
        );

        session()->setFlashdata('success', 'Account created! Please log in.');
        return redirect()->to(base_url('login'));
    }

    // ── Login GET ─────────────────────────────────────────────────
    public function login(): string
    {
        if (session()->has('user')) {
            return redirect()->to(base_url('items'))->getBody();
        }
        return view('auth/login', ['title' => 'Login']);
    }

    // ── Login POST ────────────────────────────────────────────────
    public function loginPost()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return view('auth/login', [
                'title'      => 'Login',
                'validation' => $this->validator,
                'old'        => $this->request->getPost(),
            ]);
        }

        $user = $this->userModel->findByEmail($this->request->getPost('email'));

        if (!$user || !password_verify($this->request->getPost('password'), $user['password'])) {
            $this->logModel->log(
                'WARNING', 'AuthController', 'loginPost',
                'Failed login attempt for: ' . $this->request->getPost('email')
            );
            return view('auth/login', [
                'title' => 'Login',
                'error' => 'Invalid email or password.',
                'old'   => $this->request->getPost(),
            ]);
        }

        if (!$user['is_active']) {
            return view('auth/login', [
                'title' => 'Login',
                'error' => 'Your account has been disabled.',
                'old'   => $this->request->getPost(),
            ]);
        }

        session()->set('user', [
            'id'       => $user['id'],
            'username' => $user['username'],
            'email'    => $user['email'],
            'role'     => $user['role'],
            'name'     => $user['first_name'] . ' ' . $user['last_name'],
        ]);

        $this->logModel->log(
            'INFO', 'AuthController', 'loginPost',
            'User logged in: ' . $user['email'],
            $user['id']
        );

        session()->setFlashdata('success', 'Welcome back, ' . esc($user['first_name']) . '!');
        return redirect()->to(base_url('items'));
    }

    // ── Logout ────────────────────────────────────────────────────
    public function logout()
    {
        $userId = session('user')['id'] ?? null;
        $this->logModel->log('INFO', 'AuthController', 'logout', 'User logged out.', $userId);
        session()->destroy();
        return redirect()->to(base_url('login'));
    }
}