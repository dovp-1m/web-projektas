<?php

namespace App\Models;

use CodeIgniter\Model;

class ItemModel extends Model
{
    protected $table      = 'items';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id', 'category_id', 'title', 'description',
        'price', 'size', 'condition', 'brand', 'image', 'status', 'views',
    ];

    protected $useTimestamps = true;

    public function getActiveItems(int $limit = 20, int $offset = 0): array
    {
        return $this->select('items.*, users.username, categories.name as category_name')
            ->join('users', 'users.id = items.user_id')
            ->join('categories', 'categories.id = items.category_id')
            ->where('items.status', 'active')
            ->orderBy('items.created_at', 'DESC')
            ->findAll($limit, $offset);
    }

    public function getActiveFiltered(array $filters, int $limit, int $offset): array
    {
        $builder = $this->select('items.*, users.username, categories.name as category_name')
            ->join('users', 'users.id = items.user_id')
            ->join('categories', 'categories.id = items.category_id')
            ->where('items.status', 'active');

        if (!empty($filters['category_id'])) {
            $builder->where('items.category_id', $filters['category_id']);
        }
        if (!empty($filters['q'])) {
            $builder->groupStart()
                ->like('items.title', $filters['q'])
                ->orLike('items.brand', $filters['q'])
                ->groupEnd();
        }
        if (!empty($filters['condition'])) {
            $builder->where('items.condition', $filters['condition']);
        }
        if (!empty($filters['max_price'])) {
            $builder->where('items.price <=', (float)$filters['max_price']);
        }

        return $builder->orderBy('items.created_at', 'DESC')->findAll($limit, $offset);
    }

    public function countFiltered(array $filters): int
    {
        $builder = $this->select('items.id')
            ->join('categories', 'categories.id = items.category_id')
            ->where('items.status', 'active');

        if (!empty($filters['category_id'])) {
            $builder->where('items.category_id', $filters['category_id']);
        }
        if (!empty($filters['q'])) {
            $builder->groupStart()
                ->like('items.title', $filters['q'])
                ->orLike('items.brand', $filters['q'])
                ->groupEnd();
        }
        if (!empty($filters['condition'])) {
            $builder->where('items.condition', $filters['condition']);
        }
        if (!empty($filters['max_price'])) {
            $builder->where('items.price <=', (float)$filters['max_price']);
        }

        return $builder->countAllResults();
    }

    public function getItemWithDetails(int $id): ?array
    {
        return $this->select('items.*, users.username, users.first_name, users.last_name, categories.name as category_name')
            ->join('users', 'users.id = items.user_id')
            ->join('categories', 'categories.id = items.category_id')
            ->find($id);
    }

    public function getUserItems(int $userId): array
    {
        return $this->select('items.*, categories.name as category_name')
            ->join('categories', 'categories.id = items.category_id')
            ->where('items.user_id', $userId)
            ->orderBy('items.created_at', 'DESC')
            ->findAll();
    }

    public function incrementViews(int $id): void
    {
        $this->set('views', 'views + 1', false)->where('id', $id)->update();
    }
}