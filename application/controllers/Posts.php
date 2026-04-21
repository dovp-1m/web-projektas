<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Posts controller
 *
 * CRUD for posts.
 * - Any logged-in user (editor or admin) can create, edit their own posts.
 * - Admin can edit/delete any post.
 * - Public can view published posts.
 *
 * Validators (8+):
 *   1. title – required
 *   2. title – min_length[3]
 *   3. title – max_length[255]
 *   4. body  – required
 *   5. body  – min_length[20]
 *   6. excerpt – max_length[500]
 *   7. status – in_list[draft,published]
 *   8. category_id – integer, must exist
 *   9. slug – unique (custom check)
 */
class Posts extends MY_Controller
{
    private const PER_PAGE = 20;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Post_model', 'Category_model']);
        $this->load->library('pagination');
    }

    // ── LIST  GET /posts ───────────────────────────────────
    public function index(): void
    {
        $this->require_login();

        $page   = (int)($this->input->get('page') ?? 1);
        $offset = ($page - 1) * self::PER_PAGE;

        // Editors see only their own posts; admins see all
        $filters = $this->is_admin() ? [] : ['user_id' => $this->session->userdata('user_id')];

        $total  = $this->Post_model->count_all($filters);
        $posts  = $this->Post_model->get_all(self::PER_PAGE, $offset, $filters);

        // Pagination config
        $pagConfig = [
            'base_url'    => site_url('posts'),
            'total_rows'  => $total,
            'per_page'    => self::PER_PAGE,
            'use_page_numbers' => TRUE,
            'query_string_segment' => 'page',
            'full_tag_open'  => '<ul class="pagination">',
            'full_tag_close' => '</ul>',
            'first_tag_open' => '<li class="page-item">',
            'first_tag_close'=> '</li>',
            'last_tag_open'  => '<li class="page-item">',
            'last_tag_close' => '</li>',
            'next_tag_open'  => '<li class="page-item">',
            'next_tag_close' => '</li>',
            'prev_tag_open'  => '<li class="page-item">',
            'prev_tag_close' => '</li>',
            'num_tag_open'   => '<li class="page-item">',
            'num_tag_close'  => '</li>',
            'cur_tag_open'   => '<li class="page-item active"><a class="page-link" href="#">',
            'cur_tag_close'  => '</a></li>',
            'attributes'     => ['class' => 'page-link'],
        ];
        $this->pagination->initialize($pagConfig);

        $this->render('posts/index', [
            'title'       => 'My Posts',
            'posts'       => $posts,
            'total'       => $total,
            'page'        => $page,
            'pagination'  => $this->pagination->create_links(),
        ]);
    }

    // ── DETAIL  GET /posts/view/:id ────────────────────────
    public function view(int $id): void
    {
        $post = $this->Post_model->get_by_id($id);
        if (!$post) { show_404(); }

        // Only admins/authors can preview drafts
        if ($post['status'] !== 'published') {
            $this->require_login();
            if (!$this->is_admin() && $post['user_id'] != $this->session->userdata('user_id')) {
                show_404();
            }
        } else {
            $this->Post_model->increment_views($id);
        }

        $this->render('posts/view', ['title' => $post['title'], 'post' => $post]);
    }

    // ── CREATE FORM  GET /posts/create ─────────────────────
    public function create(): void
    {
        $this->require_login();
        $cats = $this->Category_model->get_all();
        $this->render('posts/create', ['title' => 'New Post', 'categories' => $cats]);
    }

    // ── STORE  POST /posts/store ───────────────────────────
    public function store(): void
    {
        $this->require_login();
        $this->_set_validation_rules();

        if ($this->form_validation->run() === FALSE) {
            $this->Log_model->warning('Posts', 'store', 'Post creation validation failed');
            $cats = $this->Category_model->get_all();
            $this->render('posts/create', ['title' => 'New Post', 'categories' => $cats]);
            return;
        }

        $slug = $this->_make_unique_slug($this->input->post('title', TRUE));

        $id = $this->Post_model->create([
            'title'       => $this->input->post('title',       TRUE),
            'slug'        => $slug,
            'body'        => $this->input->post('body',        TRUE),
            'excerpt'     => $this->input->post('excerpt',     TRUE),
            'category_id' => (int)$this->input->post('category_id'),
            'status'      => $this->input->post('status',      TRUE),
            'user_id'     => (int)$this->session->userdata('user_id'),
        ]);

        $this->Log_model->info('Posts', 'store', "Post #$id created: " . $this->input->post('title', TRUE));
        $this->session->set_flashdata('success', 'Post created successfully.');
        redirect('posts');
    }

    // ── EDIT FORM  GET /posts/edit/:id ─────────────────────
    public function edit(int $id): void
    {
        $this->require_login();
        $post = $this->Post_model->get_by_id($id);
        if (!$post) { show_404(); }
        $this->_check_ownership($post);

        $cats = $this->Category_model->get_all();
        $this->render('posts/edit', ['title' => 'Edit Post', 'post' => $post, 'categories' => $cats]);
    }

    // ── UPDATE  POST /posts/update/:id ────────────────────
    public function update(int $id): void
    {
        $this->require_login();
        $post = $this->Post_model->get_by_id($id);
        if (!$post) { show_404(); }
        $this->_check_ownership($post);

        $this->_set_validation_rules();

        if ($this->form_validation->run() === FALSE) {
            $this->Log_model->warning('Posts', 'update', "Update validation failed for post #$id");
            $cats = $this->Category_model->get_all();
            $this->render('posts/edit', ['title' => 'Edit Post', 'post' => $post, 'categories' => $cats]);
            return;
        }

        $this->Post_model->update($id, [
            'title'       => $this->input->post('title',       TRUE),
            'body'        => $this->input->post('body',        TRUE),
            'excerpt'     => $this->input->post('excerpt',     TRUE),
            'category_id' => (int)$this->input->post('category_id'),
            'status'      => $this->input->post('status',      TRUE),
        ]);

        $this->Log_model->info('Posts', 'update', "Post #$id updated");
        $this->session->set_flashdata('success', 'Post updated successfully.');
        redirect('posts');
    }

    // ── DELETE  GET /posts/delete/:id ─────────────────────
    public function delete(int $id): void
    {
        $this->require_login();
        $post = $this->Post_model->get_by_id($id);
        if (!$post) { show_404(); }
        $this->_check_ownership($post);

        $this->Post_model->delete($id);
        $this->Log_model->info('Posts', 'delete', "Post #$id deleted");
        $this->session->set_flashdata('success', 'Post deleted.');
        redirect('posts');
    }

    // ── Private helpers ────────────────────────────────────

    private function _set_validation_rules(): void
    {
        $this->form_validation->set_rules('title',   'Title',   'required|min_length[3]|max_length[255]');
        $this->form_validation->set_rules('body',    'Body',    'required|min_length[20]');
        $this->form_validation->set_rules('excerpt', 'Excerpt', 'max_length[500]');
        $this->form_validation->set_rules('status',  'Status',  'required|in_list[draft,published]');
        $this->form_validation->set_rules('category_id', 'Category', 'required|integer');
    }

    private function _make_unique_slug(string $title): string
    {
        $base = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $title));
        $base = trim($base, '-');
        $slug = $base;
        $i    = 1;
        while ($this->Post_model->slug_exists($slug)) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }

    private function _check_ownership(array $post): void
    {
        if (!$this->is_admin() && $post['user_id'] != $this->session->userdata('user_id')) {
            $this->Log_model->warning('Posts', '_check_ownership',
                "Unauthorized access attempt to post #" . $post['id']);
            $this->session->set_flashdata('error', 'You do not own this post.');
            redirect('posts');
        }
    }
}
