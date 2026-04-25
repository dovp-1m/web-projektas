<?php

namespace App\Controllers;

use App\Models\ItemModel;

class HomeController extends BaseController
{
    public function index(): string
    {
        $itemModel = new ItemModel();
        $items = $itemModel->getActiveItems(8);

        return view('home/index', [
            'title' => 'Home',
            'items' => $items,
        ]);
    }
}