<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Post_model
 *
 * Full CRUD for the `posts` table.
 * Non-FK fields (8): title, slug, body, excerpt,
 *                    status, featured_image, views, published_at
 * FK fields:         category_id, user_id  (not counted per requirements)
 */
class Post_model extends CI_Model
{
    // ── Select ─────────────────────────────────────────────

    /**
     * Return a paginated list, joined with categories and users.
     */
    public function get_all(int $limit = 20, int $offset = 0, array $filters = []): array
    {
        $this->db->select('p.*, c.name AS category_name, c.color AS category_color,
                           u.username AS author')
                 ->from('posts p')
                 ->join('categories c', 'c.id = p.category_id', 'left')
                 ->join('users u',      'u.id = p.user_id',     'left');

        if (!empty($filters['status'])) {
            $this->db->where('p.status', $filters['status']);
        }
        if (!empty($filters['category_id'])) {
            $this->db->where('p.category_id', $filters['category_id']);
        }
        if (!empty($filters['user_id'])) {
            $this->db->where('p.user_id', $filters['user_id']);
        }

        return $this->db->order_by('p.created_at', 'DESC')
                        ->limit($limit, $offset)
                        ->get()
                        ->result_array();
    }

    public function count_all(array $filters = []): int
    {
        $this->db->from('posts p');
        if (!empty($filters['status']))      { $this->db->where('p.status', $filters['status']); }
        if (!empty($filters['category_id'])) { $this->db->where('p.category_id', $filters['category_id']); }
        if (!empty($filters['user_id']))     { $this->db->where('p.user_id', $filters['user_id']); }
        return $this->db->count_all_results();
    }

    public function get_by_id(int $id): ?array
    {
        $row = $this->db
            ->select('p.*, c.name AS category_name, c.color AS category_color, u.username AS author')
            ->from('posts p')
            ->join('categories c', 'c.id = p.category_id', 'left')
            ->join('users u',      'u.id = p.user_id',     'left')
            ->where('p.id', $id)
            ->get()->row_array();
        return $row ?: null;
    }

    public function get_by_slug(string $slug): ?array
    {
        $row = $this->db
            ->select('p.*, c.name AS category_name, c.color AS category_color, u.username AS author')
            ->from('posts p')
            ->join('categories c', 'c.id = p.category_id', 'left')
            ->join('users u',      'u.id = p.user_id',     'left')
            ->where('p.slug', $slug)
            ->get()->row_array();
        return $row ?: null;
    }

    // ── Slug uniqueness ────────────────────────────────────

    public function slug_exists(string $slug, ?int $exceptId = null): bool
    {
        $this->db->where('slug', $slug);
        if ($exceptId !== null) {
            $this->db->where('id !=', $exceptId);
        }
        return $this->db->count_all_results('posts') > 0;
    }

    // ── Insert ─────────────────────────────────────────────

    public function create(array $data): int
    {
        $now = date('Y-m-d H:i:s');
        $this->db->insert('posts', [
            'title'          => $data['title'],
            'slug'           => $data['slug'],
            'body'           => $data['body'],
            'excerpt'        => $data['excerpt']        ?? '',
            'category_id'    => $data['category_id']    ?? null,
            'user_id'        => $data['user_id']        ?? null,
            'status'         => $data['status']         ?? 'draft',
            'featured_image' => $data['featured_image'] ?? null,
            'views'          => 0,
            'published_at'   => ($data['status'] === 'published') ? $now : null,
            'created_at'     => $now,
            'updated_at'     => $now,
        ]);
        return (int)$this->db->insert_id();
    }

    // ── Update ─────────────────────────────────────────────

    public function update(int $id, array $data): bool
    {
        $existing = $this->get_by_id($id);
        $data['updated_at'] = date('Y-m-d H:i:s');

        // Set published_at only when transitioning to 'published'
        if (isset($data['status']) && $data['status'] === 'published'
                && $existing && $existing['status'] !== 'published') {
            $data['published_at'] = date('Y-m-d H:i:s');
        }

        $this->db->where('id', $id)->update('posts', $data);
        return $this->db->affected_rows() > 0;
    }

    // ── Delete ─────────────────────────────────────────────

    public function delete(int $id): bool
    {
        $this->db->delete('posts', ['id' => $id]);
        return $this->db->affected_rows() > 0;
    }

    // ── Increment view counter ─────────────────────────────

    public function increment_views(int $id): void
    {
        $this->db->set('views', 'views + 1', FALSE)
                 ->where('id', $id)
                 ->update('posts');
    }
}
