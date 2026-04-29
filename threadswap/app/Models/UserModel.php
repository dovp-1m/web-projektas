<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'username', 'email', 'password',
        'first_name', 'last_name', 'bio', 'avatar', 'role', 'is_active',
    ];

    protected $useTimestamps = true;

    // Hash password automatically before insert or update
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data): array
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash(
                $data['data']['password'],
                PASSWORD_BCRYPT
            );
        }
        return $data;
    }

    public function findByEmail(string $email): ?array
    {
        return $this->where('email', $email)->first();
    }
}