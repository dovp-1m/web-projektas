<?php

namespace App\Controllers;

use App\Models\ItemModel;

class HomeController extends BaseController
{
    public function index(): string
    {
        $itemModel = new ItemModel();
        return view('home/index', [
            'title' => 'Home',
            'items' => $itemModel->getActiveItems(8),
        ]);
    }
}