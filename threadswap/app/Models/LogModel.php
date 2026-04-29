<?php

namespace App\Models;

use CodeIgniter\Model;

class LogModel extends Model
{
    protected $table      = 'logs';
    protected $primaryKey = 'id';

    protected $allowedFields = ['user_id', 'level', 'class', 'method', 'message'];

    protected $useTimestamps = true;
    protected $updatedField  = ''; // logs are immutable

    public function log(
        string $level,
        string $class,
        string $method,
        string $message,
        ?int   $userId = null
    ): void {
        try {
            $this->insert([
                'user_id' => $userId ?? (session()->has('user') ? session('user')['id'] : null),
                'level'   => $level,
                'class'   => $class,
                'method'  => $method,
                'message' => $message,
            ]);
        } catch (\Throwable $e) {
            log_message('error', '[LogModel] DB log failed: ' . $e->getMessage());
        }
    }
}