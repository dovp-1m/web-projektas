<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\LogModel;

class CategoryController extends BaseController
{
    protected CategoryModel $categoryModel;
    protected LogModel      $logModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
        $this->logModel      = new LogModel();
    }

    public function index(): string
    {
        return view('admin/categories/index', [
            'title'      => 'Manage Categories',
            'categories' => $this->categoryModel->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    public function create(): string
    {
        return view('admin/categories/form', ['title' => 'Create Category']);
    }

    public function store()
    {
        $rules = [
            'name'        => 'required|min_length[2]|max_length[100]|is_unique[categories.name]',
            'description' => 'permit_empty|max_length[500]',
        ];

        if (!$this->validate($rules)) {
            return view('admin/categories/form', [
                'title'      => 'Create Category',
                'validation' => $this->validator,
                'old'        => $this->request->getPost(),
            ]);
        }

        $name = $this->request->getPost('name');
        $id   = $this->categoryModel->insert([
            'name'        => $name,
            'description' => $this->request->getPost('description'),
            'slug'        => $this->categoryModel->generateSlug($name),
        ]);

        $this->logModel->log('INFO', 'CategoryController', 'store',
            "Category created: {$name} (ID: {$id})", session('user')['id']);

        session()->setFlashdata('success', "Category '{$name}' created.");
        return redirect()->to(base_url('admin/categories'));
    }

    public function edit(int $id): string
    {
        $category = $this->categoryModel->find($id);
        if (!$category) {
            session()->setFlashdata('error', 'Category not found.');
            return redirect()->to(base_url('admin/categories'))->getBody();
        }

        return view('admin/categories/form', [
            'title'    => 'Edit Category',
            'category' => $category,
        ]);
    }

    public function update(int $id)
    {
        $category = $this->categoryModel->find($id);
        if (!$category) {
            session()->setFlashdata('error', 'Category not found.');
            return redirect()->to(base_url('admin/categories'));
        }

        $rules = [
            'name'        => "required|min_length[2]|max_length[100]|is_unique[categories.name,id,{$id}]",
            'description' => 'permit_empty|max_length[500]',
        ];

        if (!$this->validate($rules)) {
            return view('admin/categories/form', [
                'title'      => 'Edit Category',
                'category'   => $category,
                'validation' => $this->validator,
                'old'        => $this->request->getPost(),
            ]);
        }

        $name = $this->request->getPost('name');
        $this->categoryModel->update($id, [
            'name'        => $name,
            'description' => $this->request->getPost('description'),
            'slug'        => $this->categoryModel->generateSlug($name),
            'is_active'   => $this->request->getPost('is_active') ? 1 : 0,
        ]);

        $this->logModel->log('INFO', 'CategoryController', 'update',
            "Category updated: {$name} (ID: {$id})", session('user')['id']);

        session()->setFlashdata('success', 'Category updated.');
        return redirect()->to(base_url('admin/categories'));
    }

    public function delete(int $id)
    {
        $category = $this->categoryModel->find($id);
        if ($category) {
            $this->categoryModel->delete($id);
            $this->logModel->log('WARNING', 'CategoryController', 'delete',
                "Category deleted: {$category['name']} (ID: {$id})", session('user')['id']);
            session()->setFlashdata('success', 'Category deleted.');
        }
        return redirect()->to(base_url('admin/categories'));
    }
}