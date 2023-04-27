<?php

namespace Controllers;

class OrderController {
    
    // get all orders for manage-order view
    public function getAllOrders() : void {
        $model = new \Models\Order();
        $data = $model->getAllOrders();
        $template = 'manage-order.phtml';
        include_once 'views/layout.phtml';
    }
    
    // delete an existing order
    public function deleteOrder(string $id) : void {
        $model = new \Models\Order();
        $model->deleteOrder($id);
    }
    
    // show update order view pre-filled with id
    public function goUpdateOrder(string $id) : void {
        $model = new \Models\Order();
        $data = $model->getOrderById($id);
        $template = 'update-order.phtml';
        include_once 'views/layout.phtml';
    }
    
    // update order in tbl_order
    public function updateOrder() : void {
        $model = new \Models\Order();
        $model->updateOrder();
    }
    
}
