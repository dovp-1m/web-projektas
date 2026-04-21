<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Home controller
 *
 * Public-facing blog pages (no login required).
 */
class Home extends MY_Controller
{
    private const PER_PAGE = 10;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Post_model', 'Category_model']);
        $this->load->library('pagination');
    }

    // ── Blog index ─────────────────────────────────────────
    public function index(): void
    {
        $page   = max(1, (int)($this->input->get('page') ?? 1));
        $offset = ($page - 1) * self::PER_PAGE;

        $filters = ['status' => 'published'];
        $total   = $this->Post_model->count_all($filters);
        $posts   = $this->Post_model->get_all(self::PER_PAGE, $offset, $filters);
        $cats    = $this->Category_model->get_all();

        $this->_init_pagination(site_url('blog'), $total);

        $this->render('home/index', [
            'title'      => 'BlogCMS – Home',
            'posts'      => $posts,
            'categories' => $cats,
            'pagination' => $this->pagination->create_links(),
        ]);
    }

    // ── Posts by category ──────────────────────────────────
    public function category(string $slug): void
    {
        $cat = $this->Category_model->get_by_slug($slug);
        if (!$cat) { show_404(); }

        $page    = max(1, (int)($this->input->get('page') ?? 1));
        $offset  = ($page - 1) * self::PER_PAGE;
        $filters = ['status' => 'published', 'category_id' => $cat['id']];
        $total   = $this->Post_model->count_all($filters);
        $posts   = $this->Post_model->get_all(self::PER_PAGE, $offset, $filters);
        $cats    = $this->Category_model->get_all();

        $this->_init_pagination(site_url("blog/category/$slug"), $total);

        $this->render('home/index', [
            'title'           => 'Category: ' . $cat['name'],
            'posts'           => $posts,
            'categories'      => $cats,
            'active_category' => $cat,
            'pagination'      => $this->pagination->create_links(),
        ]);
    }

    // ── Single post ────────────────────────────────────────
    public function post(string $slug): void
    {
        $post = $this->Post_model->get_by_slug($slug);
        if (!$post || $post['status'] !== 'published') { show_404(); }

        $this->Post_model->increment_views((int)$post['id']);

        $this->render('home/post', [
            'title' => $post['title'],
            'post'  => $post,
        ]);
    }

    // ── 404 page ──────────────────────────────────────────
    public function not_found(): void
    {
        $this->output->set_status_header(404);
        $this->render('home/404', ['title' => 'Page Not Found']);
    }

    // ── Helper ────────────────────────────────────────────
    private function _init_pagination(string $baseUrl, int $total): void
    {
        $this->pagination->initialize([
            'base_url'             => $baseUrl,
            'total_rows'           => $total,
            'per_page'             => self::PER_PAGE,
            'use_page_numbers'     => TRUE,
            'query_string_segment' => 'page',
            'full_tag_open'   => '<ul class="pagination justify-content-center">',
            'full_tag_close'  => '</ul>',
            'num_tag_open'    => '<li class="page-item">',
            'num_tag_close'   => '</li>',
            'cur_tag_open'    => '<li class="page-item active"><a class="page-link" href="#">',
            'cur_tag_close'   => '</a></li>',
            'next_tag_open'   => '<li class="page-item">',
            'next_tag_close'  => '</li>',
            'prev_tag_open'   => '<li class="page-item">',
            'prev_tag_close'  => '</li>',
            'attributes'      => ['class' => 'page-link'],
        ]);
    }
}
