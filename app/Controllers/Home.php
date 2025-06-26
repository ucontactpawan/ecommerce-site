<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Home extends BaseController
{
    public function index()
    {
        $model = new ProductModel();
        $data['products'] = $model->findAll();
        $data['electronics'] = $model->where('category', 'electronics')->findAll();
        return view('home', $data);
    }
}
