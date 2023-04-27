<?php

namespace Models;

class Order extends Database
{
    // to fetch all registered categories, using findAll method from databaseModel
    public function getAllOrders() : array {
        $sql = "SELECT * FROM orders";
        return $this->findAll($sql);
    }
    
    // get one order by its id
    public function getOrderById(string $id) : array {
        $sql = "SELECT * FROM orders WHERE id=$id";
        return $this->getOneById($sql);
    }
    
    // delete order from order
    public function deleteOrder(string $id) : void {
        $cat = $this->getOrderById($id); // gather the info before deleting
        $sql = "DELETE FROM orders WHERE id = :id";
        $query = $this->bdd->prepare($sql);
        $query->bindParam(':id', $id, \PDO::PARAM_STR);
        $res = $query->execute();

        if($res==true)  {
            // query succesfull deleted
            header('Location: index.php?road=manageOrder');
        }
        else {
            header('Location: index.php?road=manageOrder');
        }
    }
    
    
    // update an existing order
    public function updateOrder() : void  {
        if(isset($_POST['submit'])) {
            $id = $_POST['id'];
            $quantity = $_POST ['quantity'];
            $price = $_POST['price'];
            $address = $_POST ['address'];
            $status = $_POST ['status'];

            
            // update the database
            $sql = "UPDATE orders SET
                    quantity = :quantity,
                    article_price = :price,
                    customer_address = :address,
                    status = :status
            WHERE id = :id
            ";
            
            $query = $this->bdd->prepare($sql);
            $query->bindParam(':quantity', $quantity, \PDO::PARAM_INT);
            $query->bindParam(':price', $price, \PDO::PARAM_STR);
            $query->bindParam(':address', $address, \PDO::PARAM_STR);
            $query->bindParam(':status', $status, \PDO::PARAM_STR);
            $query->bindParam(':id', $id, \PDO::PARAM_STR);
            $res = $query->execute();
            $query->closeCursor(); 
            
            if($res == true) {
                // order updated
                $_SESSION['update'] = "Order updated successfully.";
                header('Location: index.php?road=manageOrder');
            }
            else {
                // order update failed
                $_SESSION['update'] = "Failed to update order.";
                header('Location: index.php?road=manageOrder');
            }
        }
    }
}