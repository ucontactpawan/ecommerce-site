<?php 

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users'; 
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'email', 'password']; 

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // you can add more methods here
}