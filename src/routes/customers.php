<?php


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


$app = new \Slim\App;


//Get all customers

$app->get('/api/customers', function(request $request , response $response){
    
    $sql = "SELECT * FROM customers";


    try {

        $db = new Connection;
        $stmt = $db->connect()->query($sql);
        $customers = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;        
        echo json_encode($customers);


        
    } catch (PDOEXCEPTION $e) {
        echo '{"error" : {"text" : '.$e->getMessage().'}';
    }

});



//Get single customer by id

    $app->get('/api/customer/{id}', function(request $request , response $response){
    
    $id = $request->getAttribute('id');
    $sql = "SELECT * FROM customers where id = $id";


    try {

        $db = new Connection;
        $stmt = $db->connect()->query($sql);
        $customer = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;        
        echo json_encode($customer);


        
    } catch (PDOEXCEPTION $e) {
        echo '{"error" : {"text" : '.$e->getMessage().'}';
    }

});


// Add customer

$app->post('/api/customers/add', function (request $request , response $response){
       
    class Add extends Connection {
            public $first_name;
            public $last_name;
            public $phone;
            public $email;
            public $address;
            public $city;
            public $state;

            
            public function addCustomer(){


                    $_POST = json_decode(file_get_contents('php://input'), true);

                    $this->first_name = $_POST['first_name'];
                    $this->last_name = $_POST['last_name'];
                    $this->phone = $_POST['phone'];
                    $this->email = $_POST['email'];
                    $this->address = $_POST['address'];
                    $this->city = $_POST['city'];
                    $this->state = $_POST['state'];
    
                    $sql = "INSERT INTO customers (first_name, last_name, phone, email, address, city, state) VALUES(:first_name, :last_name, :phone, :email, :address, :city, :state)";
                    

                    $stmt = $this->connect()->prepare($sql);
                    $stmt->execute(array(
                        ':first_name' => $this->first_name,
                        ':last_name' => $this->last_name,
                        ':phone' => $this->phone,
                        ':email' => $this->email,
                        ':address' => $this->address,
                        ':city' => $this->city,
                        ':state' => $this->state
                    ));
                    
            }

        }


            $object = new Add;
            $object->addCustomer();
            if(isset($object)){
                echo "Customer added successfully";
            }

});


//update customer

$app->put('/api/customer/update/{id}', function(request $request,  response $response) {
    $id = $request->getAttribute('id');
    
    $first_name = $request->getParam('first_name');
    $last_name = $request->getParam('last_name');
    $phone = $request->getParam('phone');
    $email = $request->getParam('email');
    $address = $request->getParam('address');
    $city = $request->getParam('first_name');
    $state = $request->getParam('state');


    $sql = "UPDATE customers SET 
        first_name = :first_name,
        last_name = :last_name,
        phone = :phone,
        email = :email,
        address = :address,
        city = :city,
        state = :state
    
        WHERE id = $id";


    try {
        $conn = new Connection;
        $conn = $conn-> connect();
        
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':state', $state);

        $stmt->execute();


        echo "customer updated successfully!";


    } catch (PDOEXCEPTION $e) {
        echo 'something went wrong!';
    }
    


});


// Delete customer by id

$app->delete('/api/customer/delete/{id}', function(request $request , response $response){
    
    $id = $request->getAttribute('id');
    $sql = "DELETE FROM customers where id = $id";


    try {

        $stmt = new Connection;
        $stmt = $stmt->connect()->prepare($sql);
        $stmt->execute();
        $db = null;        

        echo 'customer deleted !';
        
    } catch (PDOEXCEPTION $e) {
        echo 'something went wrong!';
    }

});
