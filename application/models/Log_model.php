<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Log_model
 *
 * Persists structured log entries to the `logs` database table.
 * Auto-loaded so every controller can call $this->Log_model->log(...)
 *
 * Each record stores:
 *   level       – INFO | WARNING | ERROR
 *   class_name  – which class generated the log
 *   method_name – which method generated the log
 *   message     – human-readable description
 *   user_id     – the logged-in user (or null)
 *   ip_address  – client IP
 */
class Log_model extends CI_Model
{
    const INFO    = 'INFO';
    const WARNING = 'WARNING';
    const ERROR   = 'ERROR';

    // ── Write a log entry ──────────────────────────────────
    public function log(
        string $level,
        string $class,
        string $method,
        string $message,
        ?int   $userId = null
    ): void {
        $this->db->insert('logs', [
            'level'       => strtoupper($level),
            'class_name'  => $class,
            'method_name' => $method,
            'message'     => $message,
            'user_id'     => $userId ?? $this->session->userdata('user_id'),
            'ip_address'  => $this->input->ip_address(),
            'created_at'  => date('Y-m-d H:i:s'),
        ]);
    }

    // ── Shorthand helpers ──────────────────────────────────
    public function info(string $class, string $method, string $msg, ?int $uid = null): void
    {
        $this->log(self::INFO, $class, $method, $msg, $uid);
    }

    public function warning(string $class, string $method, string $msg, ?int $uid = null): void
    {
        $this->log(self::WARNING, $class, $method, $msg, $uid);
    }

    public function error(string $class, string $method, string $msg, ?int $uid = null): void
    {
        $this->log(self::ERROR, $class, $method, $msg, $uid);
    }

    // ── Retrieve entries (admin only) ──────────────────────
    public function get_all(int $limit = 50, int $offset = 0): array
    {
        return $this->db
            ->order_by('created_at', 'DESC')
            ->get('logs', $limit, $offset)
            ->result_array();
    }

    public function count_all(): int
    {
        return $this->db->count_all('logs');
    }
}
