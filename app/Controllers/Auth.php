<?php 

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function register()
    {
        return view('auth/register');
    }

    public function registerSave()
    {
        $users = new UserModel();

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT)
        ];

        $users->save($data);
        return redirect()->to('/login');

    }

    public function login()
    {   
          helper('url');
        return view('auth/login');
    }

    public function loginAuth()
    {
        $session = session();
        $users = new UserModel();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $users->where('email', $email)->first();

        if($user && password_verify($password, $user['password'])){
            $session->set('id', $user['id']);
            return redirect()->to('/');
        }else {
             return redirect()->back()->with('error', 'Invalid credentials');
        }
    }

    function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}