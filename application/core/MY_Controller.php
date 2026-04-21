<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * MY_Controller  (extends CI_Controller)
 *
 * Every application controller extends this class.
 * Provides:
 *   - require_login()   – redirect to login if not authenticated
 *   - require_admin()   – redirect home if not admin
 *   - is_logged_in()    – boolean helper
 *   - current_user()    – session data array
 *   - render()          – load header + view + footer in one call
 */
class MY_Controller extends CI_Controller
{
    protected array $viewData = [];

    public function __construct()
    {
        parent::__construct();

        // Share auth state with all views
        $this->viewData['logged_in']   = $this->is_logged_in();
        $this->viewData['current_user'] = $this->current_user();
        $this->viewData['is_admin']     = $this->is_admin();
    }

    // ── Auth helpers ───────────────────────────────────────

    protected function is_logged_in(): bool
    {
        return (bool) $this->session->userdata('user_id');
    }

    protected function is_admin(): bool
    {
        return $this->session->userdata('role') === 'admin';
    }

    protected function current_user(): ?array
    {
        if (!$this->is_logged_in()) return null;
        return [
            'id'       => $this->session->userdata('user_id'),
            'username' => $this->session->userdata('username'),
            'role'     => $this->session->userdata('role'),
        ];
    }

    protected function require_login(): void
    {
        if (!$this->is_logged_in()) {
            $this->session->set_flashdata('error', 'Please log in to access that page.');
            redirect('login');
        }
    }

    protected function require_admin(): void
    {
        $this->require_login();
        if (!$this->is_admin()) {
            $this->session->set_flashdata('error', 'Access denied – admin only.');
            redirect('blog');
        }
    }

    // ── Render helper ──────────────────────────────────────

    /**
     * Load header, then the given view, then footer.
     * Merges extra data with the shared viewData.
     */
    protected function render(string $view, array $data = []): void
    {
        $merged = array_merge($this->viewData, $data);
        $this->load->view('templates/header', $merged);
        $this->load->view($view, $merged);
        $this->load->view('templates/footer', $merged);
    }
}
