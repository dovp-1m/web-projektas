<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User_model
 *
 * Handles all user-related database operations.
 * Passwords are stored as bcrypt hashes (PHP password_hash / password_verify).
 */
class User_model extends CI_Model
{
    // ── Retrieve ───────────────────────────────────────────

    public function get_by_id(int $id): ?array
    {
        $row = $this->db->get_where('users', ['id' => $id])->row_array();
        return $row ?: null;
    }

    public function get_by_username(string $username): ?array
    {
        $row = $this->db->get_where('users', ['username' => $username])->row_array();
        return $row ?: null;
    }

    public function get_by_email(string $email): ?array
    {
        $row = $this->db->get_where('users', ['email' => $email])->row_array();
        return $row ?: null;
    }

    public function get_all(int $limit = 20, int $offset = 0): array
    {
        return $this->db
            ->order_by('created_at', 'DESC')
            ->get('users', $limit, $offset)
            ->result_array();
    }

    public function count_all(): int
    {
        return $this->db->count_all('users');
    }

    // ── Username / email existence checks ──────────────────

    public function username_exists(string $username): bool
    {
        return $this->db->get_where('users', ['username' => $username])->num_rows() > 0;
    }

    public function email_exists(string $email): bool
    {
        return $this->db->get_where('users', ['email' => $email])->num_rows() > 0;
    }

    // ── Insert ─────────────────────────────────────────────

    /**
     * Register a new user.
     * Password is hashed here (NEVER store plain-text).
     */
    public function create(array $data): int
    {
        $this->db->insert('users', [
            'username'   => $data['username'],
            'email'      => $data['email'],
            'password'   => password_hash($data['password'], PASSWORD_BCRYPT),
            'role'       => $data['role'] ?? 'editor',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        return (int)$this->db->insert_id();
    }

    // ── Update ─────────────────────────────────────────────

    public function update(int $id, array $data): bool
    {
        if (isset($data['password']) && $data['password'] !== '') {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        } else {
            unset($data['password']);
        }
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id)->update('users', $data);
        return $this->db->affected_rows() > 0;
    }

    // ── Delete ─────────────────────────────────────────────

    public function delete(int $id): bool
    {
        $this->db->delete('users', ['id' => $id]);
        return $this->db->affected_rows() > 0;
    }

    // ── Authentication ─────────────────────────────────────

    /**
     * Verify username + password.
     * Returns the user array on success, null on failure.
     */
    public function authenticate(string $username, string $password): ?array
    {
        $user = $this->get_by_username($username);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return null;
    }
}
