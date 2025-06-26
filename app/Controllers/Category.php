<?php  

namespace App\Controllers;
use CodeIgniter\Controller;


class Category extends Controller 
{
    public function index($category)
    {
        //load json
        $jsonPath = APPPATH . 'Config/products.json';
        $jsonData = json_decode(file_get_contents($jsonPath), true);
        // get product from json by category 

        $products = isset($jsonData[$category]) ? $jsonData[$category] : [];

        $data['products'] = $products;
        $data['category'] = ucfirst($category);

        //Loding the view

     
     
        echo view('templates/header'); 
        echo view('category_view', $data);
       

    }
}