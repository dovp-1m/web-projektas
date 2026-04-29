<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ItemModel;
use App\Models\LogModel;

class AdminController extends BaseController
{
    protected UserModel $userModel;
    protected ItemModel $itemModel;
    protected LogModel  $logModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->itemModel = new ItemModel();
        $this->logModel  = new LogModel();
    }

    public function index(): string
    {
        return view('admin/index', [
            'title'      => 'Admin Dashboard',
            'userCount'  => $this->userModel->countAll(),
            'itemCount'  => $this->itemModel->countAll(),
            'recentLogs' => $this->logModel->orderBy('id', 'DESC')->findAll(10),
        ]);
    }

    public function users(): string
    {
        return view('admin/users', [
            'title' => 'Manage Users',
            'users' => $this->userModel->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    public function items(): string
    {
        $items = $this->itemModel
            ->select('items.*, users.username')
            ->join('users', 'users.id = items.user_id')
            ->orderBy('items.id', 'DESC')
            ->findAll();

        return view('admin/items', [
            'title' => 'All Listings',
            'items' => $items,
        ]);
    }

    public function deleteItem(int $id)
    {
        $item = $this->itemModel->find($id);
        if ($item) {
            if ($item['image'] && file_exists(ROOTPATH . 'public/uploads/' . $item['image'])) {
                unlink(ROOTPATH . 'public/uploads/' . $item['image']);
            }
            $this->itemModel->delete($id);
            $this->logModel->log('WARNING', 'AdminController', 'deleteItem',
                "Admin deleted item: '{$item['title']}' (ID: {$id})", session('user')['id']);
            session()->setFlashdata('success', 'Item deleted by admin.');
        }
        return redirect()->to(base_url('admin/items'));
    }

    public function logs(): string
    {
        return view('admin/logs', [
            'title' => 'System Logs',
            'logs'  => $this->logModel->orderBy('id', 'DESC')->findAll(200),
        ]);
    }
}