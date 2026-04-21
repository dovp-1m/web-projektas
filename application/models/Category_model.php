<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Category_model
 *
 * Full CRUD for the `categories` table.
 * Fields: name, slug, description, color
 */
class Category_model extends CI_Model
{
    // ── Select ─────────────────────────────────────────────

    public function get_all(): array
    {
        return $this->db->order_by('name')->get('categories')->result_array();
    }

    public function get_by_id(int $id): ?array
    {
        $row = $this->db->get_where('categories', ['id' => $id])->row_array();
        return $row ?: null;
    }

    public function get_by_slug(string $slug): ?array
    {
        $row = $this->db->get_where('categories', ['slug' => $slug])->row_array();
        return $row ?: null;
    }

    public function count_all(): int
    {
        return $this->db->count_all('categories');
    }

    // ── Slug uniqueness check ──────────────────────────────

    public function slug_exists(string $slug, ?int $exceptId = null): bool
    {
        $this->db->where('slug', $slug);
        if ($exceptId !== null) {
            $this->db->where('id !=', $exceptId);
        }
        return $this->db->count_all_results('categories') > 0;
    }

    // ── Insert ─────────────────────────────────────────────

    public function create(array $data): int
    {
        $this->db->insert('categories', [
            'name'        => $data['name'],
            'slug'        => $data['slug'],
            'description' => $data['description'] ?? '',
            'color'       => $data['color']       ?? '#0d6efd',
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);
        return (int)$this->db->insert_id();
    }

    // ── Update ─────────────────────────────────────────────

    public function update(int $id, array $data): bool
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id)->update('categories', $data);
        return $this->db->affected_rows() > 0;
    }

    // ── Delete ─────────────────────────────────────────────

    public function delete(int $id): bool
    {
        $this->db->delete('categories', ['id' => $id]);
        return $this->db->affected_rows() > 0;
    }
}
