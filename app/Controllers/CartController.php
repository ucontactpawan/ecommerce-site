<?php 

namespace App\Controllers;

use App\Models\CartModel;
use App\Models\ProductModel;

class CartController extends BaseController
{
    public function addToCart()
    {
        $cartModel = new CartModel();
        $productId = $this->request->getPost('product_id');
        $userId = session()->get('id');
        $isAjax = $this->request->isAJAX();

        if (!$userId) {
            if ($isAjax) {
                return $this->response->setStatusCode(401)->setJSON([
                    'success' => false,
                    'message' => 'You must be logged in to add items to the cart.'
                ]);
            }
            return redirect()->to('/login')->with('error', 'You must be logged in to add items to the cart.');
        }

        try {
            // check if the product is already in cart
            $existingCartItem = $cartModel->where('product_id', $productId)
                                          ->where('user_id', $userId)
                                          ->first();

            if($existingCartItem){
                // Update quantity if product already exists in cart
                $cartModel->update($existingCartItem['id'], [
                    'quantity' => $existingCartItem['quantity'] + 1
                ]);
            } else {
                // Add new product to cart
                $cartModel->save([
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'quantity' => 1
                ]);
            }

            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Item added to cart successfully'
                ]);
            }

            return redirect()->back()->with('success', 'Item added to cart successfully');

        } catch (\Exception $e) {
            log_message('error', 'Error adding to cart: ' . $e->getMessage());
            if ($isAjax) {
                return $this->response->setStatusCode(500)->setJSON([
                    'success' => false,
                    'message' => 'Failed to add item to cart'
                ]);
            }
            return redirect()->back()->with('error', 'Failed to add item to cart');
        }
    }

    public function viewCart()
    {
        $cartModel = new CartModel();
        $productModel = new ProductModel();
        $userId = session()->get('id');

        $cartItems = $cartModel->where('user_id', $userId)->findAll();

        foreach ($cartItems as &$item) {
            $product = $productModel->find($item['product_id']);
            $item['product'] = $product ? $product : ['name' => 'Unknown', 'price' => 0];
        }

        return view('cart/view', [
            'cartItems' => $cartItems
        ]);
    }

    public function count()
    {
        $cartModel = new CartModel();
        $userId = session()->get('id');
        
        if (!$userId) {
            return $this->response->setJSON(['count' => 0]);
        }

        $count = $cartModel->where('user_id', $userId)->countAllResults();
        return $this->response->setJSON(['count' => $count]);
    }

    public function removeFromCart($id)
    {
        try {
            if (!session()->get('id')) {
                return $this->response->setStatusCode(401)
                    ->setJSON(['success' => false, 'message' => 'Please login to remove items']);
            }

            $cartModel = new CartModel();
            $cartItem = $cartModel->where([
                'id' => $id,
                'user_id' => session()->get('id')
            ])->first();

            if (!$cartItem) {
                return $this->response->setStatusCode(404)
                    ->setJSON(['success' => false, 'message' => 'Cart item not found']);
            }

            if ($cartModel->delete($id)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Product removed successfully'
                ]);
            }

            return $this->response->setStatusCode(500)
                ->setJSON(['success' => false, 'message' => 'Failed to remove item']);

        } catch (\Exception $e) {
            log_message('error', 'Error removing cart item: ' . $e->getMessage());
            return $this->response->setStatusCode(500)
                ->setJSON(['success' => false, 'message' => 'An error occurred']);
        }
    }

    public function getCartCount()
    {
        $cartModel = new CartModel();
        $userId = session()->get('id');

        if (!$userId) {
            return $this->response->setJSON(['count' => 0]);
        }

        $totalQuantity = $cartModel->where('user_id', $userId)
                                   ->selectSum('quantity')
                                   ->first();

        $count = $totalQuantity ? $totalQuantity['quantity'] : 0;
        return $this->response->setJSON(['count' => $count]);
    }


    public function updateQuantity($id, $quantity)
    {
        $cartModel = new CartModel();
        $userId = session()->get('id');

        if (!$userId) {
            return $this->response->setStatusCode(401)
                ->setJSON(['success' => false, 'message' => 'User not logged in']);
        }

        $cartItem = $cartModel->where([
            'id' => $id,
            'user_id' => $userId
        ])->first();

        if (!$cartItem) {
            return $this->response->setStatusCode(404)
                ->setJSON(['success' => false, 'message' => 'Item not found in cart']);
        }

        if ((int)$quantity < 1) {
            return $this->response->setStatusCode(400)
                ->setJSON(['success' => false, 'message' => 'Invalid quantity']);
        }

        $cartModel->update($id, ['quantity' => $quantity]);
        return $this->response->setJSON(['success' => true]);
    }
}
