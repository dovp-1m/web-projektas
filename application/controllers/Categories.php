<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Categories controller
 *
 * Full CRUD for categories.
 * ADMIN ONLY – all methods call require_admin().
 */
class Categories extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Category_model');
    }

    // ── LIST ──────────────────────────────────────────────
    public function index(): void
    {
        $this->require_admin();
        $cats = $this->Category_model->get_all();
        $this->render('categories/index', ['title' => 'Categories', 'categories' => $cats]);
    }

    // ── CREATE FORM ───────────────────────────────────────
    public function create(): void
    {
        $this->require_admin();
        $this->render('categories/create', ['title' => 'New Category']);
    }

    // ── STORE ─────────────────────────────────────────────
    public function store(): void
    {
        $this->require_admin();

        // Validators for category
        $this->form_validation->set_rules('name',  'Name',  'required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('slug',  'Slug',  'required|min_length[2]|max_length[100]|alpha_dash');
        $this->form_validation->set_rules('color', 'Color', 'required|regex_match[/^#[0-9A-Fa-f]{6}$/]',
            ['regex_match' => 'Color must be a valid hex colour (e.g. #ff5733).']);
        $this->form_validation->set_rules('description', 'Description', 'max_length[1000]');

        if ($this->form_validation->run() === FALSE) {
            $this->Log_model->warning('Categories', 'store', 'Category creation validation failed');
            $this->render('categories/create', ['title' => 'New Category']);
            return;
        }

        $slug = $this->input->post('slug', TRUE);
        if ($this->Category_model->slug_exists($slug)) {
            $this->session->set_flashdata('error', "Slug '$slug' already exists.");
            redirect('categories/create');
            return;
        }

        $id = $this->Category_model->create([
            'name'        => $this->input->post('name',        TRUE),
            'slug'        => $slug,
            'description' => $this->input->post('description', TRUE),
            'color'       => $this->input->post('color',       TRUE),
        ]);

        $this->Log_model->info('Categories', 'store', "Category #$id created: $slug");
        $this->session->set_flashdata('success', 'Category created.');
        redirect('categories');
    }

    // ── EDIT FORM ─────────────────────────────────────────
    public function edit(int $id): void
    {
        $this->require_admin();
        $cat = $this->Category_model->get_by_id($id);
        if (!$cat) { show_404(); }
        $this->render('categories/edit', ['title' => 'Edit Category', 'category' => $cat]);
    }

    // ── UPDATE ────────────────────────────────────────────
    public function update(int $id): void
    {
        $this->require_admin();
        $cat = $this->Category_model->get_by_id($id);
        if (!$cat) { show_404(); }

        $this->form_validation->set_rules('name',  'Name',  'required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('slug',  'Slug',  'required|min_length[2]|max_length[100]|alpha_dash');
        $this->form_validation->set_rules('color', 'Color', 'required|regex_match[/^#[0-9A-Fa-f]{6}$/]',
            ['regex_match' => 'Color must be a valid hex colour.']);
        $this->form_validation->set_rules('description', 'Description', 'max_length[1000]');

        if ($this->form_validation->run() === FALSE) {
            $this->Log_model->warning('Categories', 'update', "Update validation failed for category #$id");
            $this->render('categories/edit', ['title' => 'Edit Category', 'category' => $cat]);
            return;
        }

        $slug = $this->input->post('slug', TRUE);
        if ($this->Category_model->slug_exists($slug, $id)) {
            $this->session->set_flashdata('error', "Slug '$slug' is already used by another category.");
            redirect("categories/edit/$id");
            return;
        }

        $this->Category_model->update($id, [
            'name'        => $this->input->post('name',        TRUE),
            'slug'        => $slug,
            'description' => $this->input->post('description', TRUE),
            'color'       => $this->input->post('color',       TRUE),
        ]);

        $this->Log_model->info('Categories', 'update', "Category #$id updated");
        $this->session->set_flashdata('success', 'Category updated.');
        redirect('categories');
    }

    // ── DELETE ────────────────────────────────────────────
    public function delete(int $id): void
    {
        $this->require_admin();
        $cat = $this->Category_model->get_by_id($id);
        if (!$cat) {
            $this->Log_model->error('Categories', 'delete',
                "Attempt to delete non-existent category #$id");
            $this->session->set_flashdata('error', 'Category not found.');
            redirect('categories');
            return;
        }

        $this->Category_model->delete($id);
        $this->Log_model->info('Categories', 'delete', "Category #$id deleted");
        $this->session->set_flashdata('success', 'Category deleted.');
        redirect('categories');
    }
}
