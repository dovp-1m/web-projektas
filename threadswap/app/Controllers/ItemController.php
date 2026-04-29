<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\CategoryModel;
use App\Models\LogModel;

class ItemController extends BaseController
{
    protected ItemModel     $itemModel;
    protected CategoryModel $categoryModel;
    protected LogModel      $logModel;

    protected const ITEMS_PER_PAGE = 12;

    public function __construct()
    {
        $this->itemModel     = new ItemModel();
        $this->categoryModel = new CategoryModel();
        $this->logModel      = new LogModel();
    }

    // ── Browse all active items ────────────────────────────────────
    public function index(): string
    {
        $filters = [
            'q'           => $this->request->getGet('q'),
            'category_id' => $this->request->getGet('category_id'),
            'condition'   => $this->request->getGet('condition'),
            'max_price'   => $this->request->getGet('max_price'),
        ];

        $page       = max(1, (int)($this->request->getGet('page') ?? 1));
        $offset     = ($page - 1) * self::ITEMS_PER_PAGE;
        $total      = $this->itemModel->countFiltered($filters);
        $items      = $this->itemModel->getActiveFiltered($filters, self::ITEMS_PER_PAGE, $offset);
        $totalPages = (int)ceil($total / self::ITEMS_PER_PAGE);

        return view('items/index', [
            'title'      => 'Browse Items',
            'items'      => $items,
            'categories' => $this->categoryModel->getActiveCategories(),
            'filters'    => $filters,
            'total'      => $total,
            'page'       => $page,
            'totalPages' => $totalPages,
        ]);
    }

    // ── View single item ───────────────────────────────────────────
    public function show(int $id): string
    {
        $item = $this->itemModel->getItemWithDetails($id);

        if (!$item || $item['status'] !== 'active') {
            session()->setFlashdata('error', 'Item not found.');
            return redirect()->to(base_url('items'))->getBody();
        }

        $this->itemModel->incrementViews($id);

        return view('items/show', [
            'title' => esc($item['title']),
            'item'  => $item,
        ]);
    }

    // ── My Listings ────────────────────────────────────────────────
    public function myListings(): string
    {
        return view('items/my_listings', [
            'title' => 'My Listings',
            'items' => $this->itemModel->getUserItems(session('user')['id']),
        ]);
    }

    // ── Create form ────────────────────────────────────────────────
    public function create(): string
    {
        return view('items/form', [
            'title'      => 'List an Item',
            'categories' => $this->categoryModel->getActiveCategories(),
        ]);
    }

    // ── Store new item ─────────────────────────────────────────────
    public function store()
    {
        $rules = [
            'title'       => 'required|min_length[3]|max_length[200]',
            'description' => 'required|min_length[10]|max_length[2000]',
            'price'       => 'required|decimal|greater_than[0]|less_than[10000]',
            'size'        => 'required|max_length[20]',
            'condition'   => 'required|in_list[new,like_new,good,fair]',
            'brand'       => 'required|min_length[2]|max_length[100]',
            'category_id' => 'required|is_natural_no_zero',
            'image'       => 'permit_empty|uploaded[image]|max_size[image,2048]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]',
        ];

        $messages = [
            'price'     => [
                'decimal'      => 'Price must be a valid number (e.g. 9.99).',
                'greater_than' => 'Price must be greater than €0.',
                'less_than'    => 'Price cannot exceed €9999.',
            ],
            'condition' => ['in_list'  => 'Please select a valid condition.'],
            'image'     => [
                'max_size'  => 'Image must be under 2 MB.',
                'is_image'  => 'The uploaded file must be a valid image.',
                'mime_in'   => 'Only JPG, PNG, and WebP images are allowed.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return view('items/form', [
                'title'      => 'List an Item',
                'categories' => $this->categoryModel->getActiveCategories(),
                'validation' => $this->validator,
                'old'        => $this->request->getPost(),
            ]);
        }

        // Handle image upload
        $imageName = null;
        $image = $this->request->getFile('image');
        if ($image && $image->isValid() && !$image->hasMoved()) {
            $imageName = $image->getRandomName();
            $image->move(ROOTPATH . 'public/uploads', $imageName);
        }

        $itemId = $this->itemModel->insert([
            'user_id'     => session('user')['id'],
            'category_id' => $this->request->getPost('category_id'),
            'title'       => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'price'       => $this->request->getPost('price'),
            'size'        => $this->request->getPost('size'),
            'condition'   => $this->request->getPost('condition'),
            'brand'       => $this->request->getPost('brand'),
            'image'       => $imageName,
        ]);

        $this->logModel->log('INFO', 'ItemController', 'store',
            "Item created: '{$this->request->getPost('title')}' (ID: {$itemId})",
            session('user')['id']);

        session()->setFlashdata('success', 'Your item has been listed!');
        return redirect()->to(base_url('items/' . $itemId));
    }

    // ── Edit form ──────────────────────────────────────────────────
    public function edit(int $id): string
    {
        $item = $this->itemModel->find($id);

        if (!$item || (int)$item['user_id'] !== (int)session('user')['id']) {
            session()->setFlashdata('error', 'Item not found or access denied.');
            return redirect()->to(base_url('my/listings'))->getBody();
        }

        return view('items/form', [
            'title'      => 'Edit Listing',
            'item'       => $item,
            'categories' => $this->categoryModel->getActiveCategories(),
        ]);
    }

    // ── Update item ────────────────────────────────────────────────
    public function update(int $id)
    {
        $item = $this->itemModel->find($id);

        if (!$item || (int)$item['user_id'] !== (int)session('user')['id']) {
            session()->setFlashdata('error', 'Access denied.');
            return redirect()->to(base_url('my/listings'));
        }

        $rules = [
            'title'       => 'required|min_length[3]|max_length[200]',
            'description' => 'required|min_length[10]|max_length[2000]',
            'price'       => 'required|decimal|greater_than[0]|less_than[10000]',
            'size'        => 'required|max_length[20]',
            'condition'   => 'required|in_list[new,like_new,good,fair]',
            'brand'       => 'required|min_length[2]|max_length[100]',
            'category_id' => 'required|is_natural_no_zero',
            'status'      => 'required|in_list[active,sold,hidden]',
        ];

        if (!$this->validate($rules)) {
            return view('items/form', [
                'title'      => 'Edit Listing',
                'item'       => $item,
                'categories' => $this->categoryModel->getActiveCategories(),
                'validation' => $this->validator,
                'old'        => $this->request->getPost(),
            ]);
        }

        // Handle optional new image
        $imageName = $item['image'];
        $newImage  = $this->request->getFile('image');
        if ($newImage && $newImage->isValid() && !$newImage->hasMoved()) {
            $imgRules = [
                'image' => 'max_size[image,2048]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]',
            ];
            if ($this->validate($imgRules)) {
                if ($imageName && file_exists(ROOTPATH . 'public/uploads/' . $imageName)) {
                    unlink(ROOTPATH . 'public/uploads/' . $imageName);
                }
                $imageName = $newImage->getRandomName();
                $newImage->move(ROOTPATH . 'public/uploads', $imageName);
            }
        }

        $this->itemModel->update($id, [
            'category_id' => $this->request->getPost('category_id'),
            'title'       => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'price'       => $this->request->getPost('price'),
            'size'        => $this->request->getPost('size'),
            'condition'   => $this->request->getPost('condition'),
            'brand'       => $this->request->getPost('brand'),
            'status'      => $this->request->getPost('status'),
            'image'       => $imageName,
        ]);

        $this->logModel->log('INFO', 'ItemController', 'update',
            "Item updated: ID {$id}", session('user')['id']);

        session()->setFlashdata('success', 'Listing updated!');
        return redirect()->to(base_url('my/listings'));
    }

    // ── Delete item ────────────────────────────────────────────────
    public function delete(int $id)
    {
        $item = $this->itemModel->find($id);

        if (!$item || (int)$item['user_id'] !== (int)session('user')['id']) {
            session()->setFlashdata('error', 'Access denied.');
            return redirect()->to(base_url('my/listings'));
        }

        if ($item['image'] && file_exists(ROOTPATH . 'public/uploads/' . $item['image'])) {
            unlink(ROOTPATH . 'public/uploads/' . $item['image']);
        }

        $this->itemModel->delete($id);

        $this->logModel->log('WARNING', 'ItemController', 'delete',
            "Item deleted: '{$item['title']}' (ID: {$id})", session('user')['id']);

        session()->setFlashdata('success', 'Listing deleted.');
        return redirect()->to(base_url('my/listings'));
    }
}