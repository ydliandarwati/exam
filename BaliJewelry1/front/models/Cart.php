<?php
namespace Models;

class Cart extends Database {
    
    // get all info for showing cart: from order for logged-in user, and from session variable otherwise    
    public function getCart() {
            if(isset($_SESSION['auth'])) {
    		    $customer_id = $_SESSION['user'] ;
	            $sql = "SELECT * FROM orders WHERE
	                customer_id = :customer_id AND
                    status = 'pending'
                    ";
                $query = $this->bdd->prepare($sql);
                $query->bindParam(':customer_id', $customer_id, \PDO::PARAM_STR);
                $query->execute();
                $data = $query->fetchAll(\PDO::FETCH_ASSOC);
                $count = $query->rowCount();

                // if any order exists for the user, put it to session variable
                // if there is no order, nothing will happen: session[cart] is not set --> empty message appears
                if ($count > 0) {
                    foreach($data as $item) {
                        $id = $item['article_id'];
                        $_SESSION["cart"][$id]['article_name'] = $item['article_name'];
                        $_SESSION["cart"][$id]['quantity'] = $item['quantity'];
                        $_SESSION["cart"][$id]['article_id'] = $item['article_id'];
                        $_SESSION["cart"][$id]['article_price'] = $item['article_price'];
                        $_SESSION["cart"][$id]['article_image'] = $item['article_image'];
                    }
                    return $data;
                }
            }
            else {
                if(isset($_SESSION["cart"])) {
                    $data = $_SESSION["cart"];
                    return $data;
                }
            }
    }


    // get info to pre-fill the from before validating the otder
    public function getCommanderData() {
        // if user is loggedin: use the info from the database
        if(isset($_SESSION['auth'])) {
    	    $customer_id = $_SESSION['user'];
    	    $sql = "SELECT * FROM user WHERE username = :customer_id";
            $query = $this->bdd->prepare($sql);
            $query->bindParam(':customer_id', $customer_id, \PDO::PARAM_STR);
            $query->execute();
            $data = $query->fetchAll(\PDO::FETCH_ASSOC);
            return array("name" => array_values($data)[0]["firstname"]."_".array_values($data)[0]["lastname"],"email" => array_values($data)[0]["email"]) ;
        }
        // if not: fill-in with some hint
        else {
            return array("name" => 'Your_full_name',"email" => "Your_email");
        }
    }

    // add item to cart
    public function addToCart() {
        $id = $_GET["id"]; // get id of selected item
        if(!empty($_POST["quantity"])) {
            // based on id, get all the info from collection table --> to put in session variable
            $sql = "SELECT * FROM collection WHERE id=:id";
            $query = $this->bdd->prepare($sql);
            $query->bindParam(':id', $id, \PDO::PARAM_STR);
            $res = $query->execute();
            $data = $query->fetchAll(\PDO::FETCH_ASSOC); // info of selected item

            // Now, we put the useful info in an array, to be added to session variable
	        $itemArray = array($id=>array('article_name'=>$data[0]["title"], 'article_id'=>$id, 
	                                                 'quantity'=>$_POST["quantity"], 'article_price'=>$data[0]["price"],
	                                                 'article_image'=>$data[0]["image_name"]));
	       
	   
		    // We have 3 cases:
		    // 1. article already exists in cart --> add the quantity
		    // 2. article doesn't exist in cart --> concatenate it to session variable
		    // 3. cart is empty --> set session variable as itemArray   
    		if(!empty($_SESSION["cart"])) {
    		    // case 1: article exists: we checked it by in_array to see if id of item is there or no
    		    // then we do a loop to find that item to copy info to session variable
    			if(in_array($id,array_keys($_SESSION["cart"]))) {
    				foreach($_SESSION["cart"] as $k => $v) {
    						if($id == $k) {
    							$_SESSION["cart"][$k]["quantity"] += $_POST["quantity"];
    						}
    				}
    			} 
    			// case 2: article is not in cart
    			else {
    				$_SESSION["cart"] = $_SESSION["cart"] + $itemArray;
    			}
    		}
    		// case 3: cart is empty
    		else {
    			$_SESSION["cart"] = $itemArray;
    		}
        }
    }
    
    // remove an item completely from the cart
    // for logged-in user: femove the info from order as well
    public function removeFromCart() {
         if(!empty($_SESSION["cart"])) {
             // do a loop to find the item with id to be removed from cart
             foreach($_SESSION["cart"] as $k => $v) {
        		if($_GET["id"] == $v['article_id']) {
        		    // unset that item from cart
                    unset($_SESSION["cart"][$k]);
                    // delete item from order
        		    if(isset($_SESSION['auth'])) {
    		            $customer_id = $_SESSION['user'];
                        $art_id = $v["article_id"];
                        $sql = "DELETE FROM orders WHERE 
                            customer_id = :customer_id AND
                            article_id = :art_id AND
                            status = 'pending'
                            ";
                        $query = $this->bdd->prepare($sql);
                        $query->bindParam(':customer_id', $customer_id, \PDO::PARAM_STR);
                        $query->bindParam(':art_id', $art_id, \PDO::PARAM_STR);
                        $res = $query->execute();
        		    }
        		}
    		    // if cart got empty after this removal: unset session variable --> "empty cart" appears
        		if(empty($_SESSION["cart"]))
        		    unset($_SESSION["cart"]);
        	}
         }
    }
    
    // emtpy the cart: unset session variable
    // for logged-in users: also remove the data from order
    public function emptyCart() {
        foreach($_SESSION["cart"] as $item) {
            if(isset($_SESSION['auth'])) {
    		    $customer_id = $_SESSION['user'];
                $art_id = $item["article_id"];
                $sql = "DELETE FROM orders WHERE 
                    customer_id = :customer_id AND
                    article_id = :art_id AND
                    status = 'pending'
                    ";
                $query = $this->bdd->prepare($sql);
                $query->bindParam(':customer_id', $customer_id, \PDO::PARAM_STR);
                $query->bindParam(':art_id', $art_id, \PDO::PARAM_STR);
                $res = $query->execute();
        	}
            unset($_SESSION["cart"]);
        }
    }
    
    // reduce quantity of an item in the cart
    public function downQty() {
        if(!empty($_SESSION["cart"])) {
            foreach($_SESSION["cart"] as $k => $v) {
        	    if($_GET["id"] == $v['article_id']) {
        	        // decrease the quantity by 1, but with min=1
    			    $_SESSION["cart"][$k]["quantity"] = max(1, $_SESSION["cart"][$k]["quantity"]-1);
        		}
        	}
        }
    }
    
    // increase quantity of an item in the cart
    public function upQty() {
        if(!empty($_SESSION["cart"])) {
            foreach($_SESSION["cart"] as $k => $v) {
        	    if($_GET["id"] == $v['article_id']) {
        	        // add one to the quantity
    			    $_SESSION["cart"][$k]["quantity"]++;
        		}
        	}
        }
    }
    
    public function validateCart() {
        if(isset($_POST['submit'])) {
            // if user is not logged-in
            if(!isset($_SESSION['auth'])) {
        		// create a unique order id for the whole command
        		$customer_id = 'unkwn';
        		$order_id =  uniqid($customer_id) ;
        		foreach($_SESSION["cart"] as $item) {
        		    $quantity = $item["quantity"];
        		    $price = $item["article_price"];
        		    $art_name = $item["article_name"];
        		    $art_id = $item["article_id"];
        		    $art_img = $item["article_image"];
        		    $total_price = $quantity * $price;
        		    $email = $_POST['email'];
        		    $name = $_POST['fullname'];
        		    $address = $_POST['shipAdress'];
        		    
        		    $sql = "INSERT INTO orders SET
                        quantity=:quantity,
                        article_price=:price,
                        article_name=:art_name,
                        article_id=:art_id,
                        article_image=:art_img,
                        total_price = :total_price,
                        order_id = :order_id,
                        customer_name = :name,
                        customer_email = :email,
                        customer_address = :address,
                        customer_id = :customer_id,
                        status = 'validated'
                        ";
                    $query = $this->bdd->prepare($sql);
                    $query->bindParam(':quantity', $quantity, \PDO::PARAM_INT);
                    $query->bindParam(':price', $price, \PDO::PARAM_STR);
                    $query->bindParam(':art_name', $art_name, \PDO::PARAM_STR);
                    $query->bindParam(':art_id', $art_id, \PDO::PARAM_STR);
                    $query->bindParam(':art_img', $art_img, \PDO::PARAM_STR);
                    $query->bindParam(':total_price', $total_price, \PDO::PARAM_STR);
                    $query->bindParam(':order_id', $order_id, \PDO::PARAM_STR);
                    $query->bindParam(':name', $name, \PDO::PARAM_STR);
                    $query->bindParam(':email', $email, \PDO::PARAM_STR);
                    $query->bindParam(':address', $address, \PDO::PARAM_STR);
                    $query->bindParam(':customer_id', $customer_id, \PDO::PARAM_STR);
                    // execute Query and Save Data in Database
                    $res = $query->execute();
                    $query->closeCursor(); 
        		}
            }
        	else {
        	    $customer_id = $_SESSION['user'];
        	    $order_id =  uniqid($customer_id) ;   
        	    foreach($_SESSION["cart"] as $item) {
            	    $price = $item["article_price"];
            	    $art_name = $item["article_name"];
            	    $art_id = $item["article_id"];
        		    $email = $_POST['email'];
        		    $name = $_POST['fullname'];
        		    $address = $_POST['shipAdress'];
            	    
            		$sql = "UPDATE orders SET
                            status='validated',
                            customer_email = :email,
                            customer_address = :address,
                            customer_name = :name,
                            order_id=:order_id
                            WHERE
                            customer_id = :customer_id AND
                            article_id = :art_id AND
                            status = 'pending'
                            ";
                    $query = $this->bdd->prepare($sql);
                    $query->bindParam(':art_id', $art_id, \PDO::PARAM_STR);
                    $query->bindParam(':order_id', $order_id, \PDO::PARAM_STR);
                    $query->bindParam(':name', $name, \PDO::PARAM_STR);
                    $query->bindParam(':email', $email, \PDO::PARAM_STR);
                    $query->bindParam(':address', $address, \PDO::PARAM_STR);
                    $query->bindParam(':customer_id', $customer_id, \PDO::PARAM_STR);
                    $res = $query->execute();
                    $query->closeCursor(); 
        	    }
        	}
        } else {
            $res = FALSE;
        }
        		    
    
        // check if Data inserted or not and display the message
        if($res==TRUE) {
            $_SESSION['order'] = "<div class='success'>You order is validated! Shipping will be processed soon.</div>";
            //page redirection
            header('Location: index.php?road=emptyCart');
        }
        else {
            //page redirection
            $_SESSION['order'] = "<div class='error'>Order validation failed!</div>";
            header('Location: index.php?road=cart');
        }
    }
    
    // save NON_EMPTY cart in order: for logged-in users
    public function saveCart() {
        if(!empty($_SESSION["cart"]) && isset($_SESSION['auth'])) {
    		$customer_id = $_SESSION['user']; // get username
    		// do a loop to find all items in the cart
    		// if item is already in order update it, otherwise add it to table
    		// order status is pending until validation
    		foreach($_SESSION["cart"] as $item) {
    		    $quantity = $item["quantity"];
    		    $price = $item["article_price"];
    		    $art_name = $item["article_name"];
    		    $art_id = $item["article_id"];
    		    $art_img = $item["article_image"];
    		    $total_price = $quantity * $price;
                $sql = "SELECT * FROM orders WHERE 
                    customer_id = :customer_id AND
                    article_id = :art_id AND
                    status = 'pending'
                    ";
                    
                $query = $this->bdd->prepare($sql);
                $query->bindParam(':customer_id', $customer_id, \PDO::PARAM_STR);
                $query->bindParam(':art_id', $art_id, \PDO::PARAM_STR);
                $res = $query->execute();
                $count = $query->rowCount();

                // if entry exists, update it
                if ($count == 1) {
        		    $sql = "UPDATE orders SET
                        quantity=:quantity,
                        total_price = :total_price
                        WHERE
                        customer_id = :customer_id AND
                        article_id = :art_id AND
                        status = 'pending'
                        ";
                    $query = $this->bdd->prepare($sql);
                    $query->bindParam(':quantity', $quantity, \PDO::PARAM_INT);
                    $query->bindParam(':total_price', $total_price, \PDO::PARAM_STR);
                    $query->bindParam(':customer_id', $customer_id, \PDO::PARAM_STR);
                    $query->bindParam(':art_id', $art_id, \PDO::PARAM_STR);
                    $res = $query->execute();
                    $query->closeCursor();                     
                }
                // if not exists, insert it into order table
                elseif ($count == 0) {
        		    $sql = "INSERT INTO orders SET
                        quantity=:quantity,
                        article_price= :price,
                        article_name = :art_name,
                        article_id = :art_id,
                        article_image = :art_img,
                        total_price = :total_price,
                        customer_id = :customer_id,
                        status = 'pending'
                        ";
                        
                    $query = $this->bdd->prepare($sql);
                    $query->bindParam(':quantity', $quantity, \PDO::PARAM_INT);
                    $query->bindParam(':price', $price, \PDO::PARAM_STR);
                    $query->bindParam(':art_name', $art_name, \PDO::PARAM_STR);
                    $query->bindParam(':art_id', $art_id, \PDO::PARAM_STR);
                    $query->bindParam(':art_img', $art_img, \PDO::PARAM_STR);
                    $query->bindParam(':total_price', $total_price, \PDO::PARAM_STR);
                    $query->bindParam(':customer_id', $customer_id, \PDO::PARAM_STR);
                    $res = $query->execute();
                    $query->closeCursor(); 
                }
    		}
        }
    }
}
