<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Admin controller
 *
 * ADMIN ONLY routes:
 *   /admin            – dashboard overview
 *   /admin/logs       – application log viewer
 *   /admin/users      – user list
 */
class Admin extends MY_Controller
{
    private const PER_PAGE = 25;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Post_model', 'Category_model', 'User_model']);
        $this->load->library('pagination');
    }

    // ── Dashboard  GET /admin ─────────────────────────────
    public function dashboard(): void
    {
        $this->require_admin();

        $this->render('admin/dashboard', [
            'title'       => 'Admin Dashboard',
            'total_posts' => $this->Post_model->count_all(),
            'total_cats'  => $this->Category_model->count_all(),
            'total_users' => $this->User_model->count_all(),
            'total_logs'  => $this->Log_model->count_all(),
            'recent_logs' => $this->Log_model->get_all(5),
            'recent_posts'=> $this->Post_model->get_all(5),
        ]);
    }

    // ── Log viewer  GET /admin/logs ───────────────────────
    public function logs(): void
    {
        $this->require_admin();

        $page   = max(1, (int)($this->input->get('page') ?? 1));
        $offset = ($page - 1) * self::PER_PAGE;
        $total  = $this->Log_model->count_all();
        $logs   = $this->Log_model->get_all(self::PER_PAGE, $offset);

        $this->pagination->initialize([
            'base_url'             => site_url('admin/logs'),
            'total_rows'           => $total,
            'per_page'             => self::PER_PAGE,
            'use_page_numbers'     => TRUE,
            'query_string_segment' => 'page',
            'full_tag_open'   => '<ul class="pagination">',
            'full_tag_close'  => '</ul>',
            'num_tag_open'    => '<li class="page-item">',
            'num_tag_close'   => '</li>',
            'cur_tag_open'    => '<li class="page-item active"><a class="page-link" href="#">',
            'cur_tag_close'   => '</a></li>',
            'attributes'      => ['class' => 'page-link'],
        ]);

        $this->render('admin/logs', [
            'title'      => 'Application Logs',
            'logs'       => $logs,
            'total'      => $total,
            'pagination' => $this->pagination->create_links(),
        ]);
    }

    // ── User list  GET /admin/users ───────────────────────
    public function users(): void
    {
        $this->require_admin();

        $page   = max(1, (int)($this->input->get('page') ?? 1));
        $offset = ($page - 1) * self::PER_PAGE;
        $total  = $this->User_model->count_all();
        $users  = $this->User_model->get_all(self::PER_PAGE, $offset);

        $this->render('admin/users', [
            'title'  => 'User Management',
            'users'  => $users,
            'total'  => $total,
        ]);
    }
}
