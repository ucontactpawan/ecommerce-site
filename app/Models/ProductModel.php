<?php 

namespace App\Models;
use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'price', 'image', ' category', 'description'];

    public function getProductsByCategory($category)
    {
        return $this->where('category', $category)->findAll();

    }
}