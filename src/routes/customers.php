<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

//get all customers
$app->get('/api/customers',function(Request $request, Response $response){
    $sql= "SELECT * FROM customers";
    
    try{ //get db object
        $db = new db();
        //connect
        $db = $db->connect();
        $stmt = $db->query($sql);
        $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($customers);
    }catch(PDOException $e){
        echo '{"Error": {"Text": '. $e->getMessage() .'}';
    }
});

//get single customers
$app->get('/api/customers/{id}',function(Request $request, Response $response){
    $id = $request->getAttribute('id');
    $sql= "SELECT * FROM customers WHERE id = $id";
    
    try{ //get db object
        $db = new db();
        //connect
        $db = $db->connect();
        $stmt = $db->query($sql);
        $customer = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($customer);
    }catch(PDOException $e){
        echo '{"Error": {"Text": '. $e->getMessage() .'}';
    }
});

//add customers POST
$app->post('/api/customers/add',function(Request $request, Response $response){
    $first_name = $request->getParam('first_name');
    $last_name = $request->getParam('last_name');
    $phone = $request->getParam('phone');
    $address = $request->getParam('address');
    $city = $request->getParam('city');
    $state = $request->getParam('state');

    $sql= "INSERT INTO customers (first_name,last_name,phone,address,city,state) 
            VALUES (:first_name,:last_name,:phone,:address,:city,:state)";
    
    try{ //get db object
        $db = new db();
        //connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':first_name',$first_name);
        $stmt->bindParam(':last_name',$last_name);
        $stmt->bindParam(':phone',$phone);
        $stmt->bindParam(':address',$address);
        $stmt->bindParam(':city',$city);
        $stmt->bindParam(':state',$state);

        $stmt->execute();
        echo '{"notice": { "text" : "Customer Added"}';
    }catch(PDOException $e){
        echo '{"Error": {"Text": '. $e->getMessage() .'}';
    }
});

//update customers POST
$app->put('/api/customers/update/{id}',function(Request $request, Response $response){
    $id = $request->getAttribute('id');
    $first_name = $request->getParam('first_name');
    $last_name = $request->getParam('last_name');
    $phone = $request->getParam('phone');
    $address = $request->getParam('address');
    $city = $request->getParam('city');
    $state = $request->getParam('state');

    $sql= "UPDATE customers SET
            first_name = :first_name,
            last_name = :last_name,
            phone = :phone,
            address = :address,
            city = :city,
            state = :state
           WHERE id = $id";
    
    try{ //get db object
        $db = new db();
        //connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':first_name',$first_name);
        $stmt->bindParam(':last_name',$last_name);
        $stmt->bindParam(':phone',$phone);
        $stmt->bindParam(':address',$address);
        $stmt->bindParam(':city',$city);
        $stmt->bindParam(':state',$state);

        $stmt->execute();

        echo '{"notice": { "text" : "Customer Updated"}';
    }catch(PDOException $e){
        echo '{"Error": {"Text": '. $e->getMessage() .'}';
    }
});

//delete customers
$app->delete('/api/customers/delete/{id}',function(Request $request, Response $response){
    $id = $request->getAttribute('id');
    $sql= "DELETE FROM customers WHERE id = $id";
    
    try{ //get db object
        $db = new db();
        //connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $db = null;
        echo '{"notice": { "text" : "Customer Deleted"}';
    }catch(PDOException $e){
        echo '{"Error": {"Text": '. $e->getMessage() .'}';
    }
});