<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

require __DIR__ .'/../bootstrap.php';
use Entity\Brands;
use Entity\Categories;
use Entity\Employees;
use Entity\Products;
use Entity\Stocks;
use Entity\Stores;

$brandRep = $entityManager->getRepository(Brands::class);
$catRep = $entityManager->getRepository(Categories::class);
$empRep = $entityManager->getRepository(Employees::class);
$proRep = $entityManager->getRepository(Products::class);
$stockRep = $entityManager->getRepository(Stocks::class);
$storeRep = $entityManager->getRepository(Stores::class);

$ReqM = $_SERVER['REQUEST_METHOD'];

// converts foreign keys IDs to Instance, to avoid type errors with Doctrine entities
function Id2Instance($k, $v, $products, $brands, $stores, $cats) {

    switch ($k) {
        case 'brand':
            return $brands->find($v);

        case 'store':
            return $stores->find($v);

        case 'product':
            return $products->find($v);

        case 'category':
            return $cats->find($v);
        
        default:
            return $v;
    }
}

function queryStringToArray($queryString) {
    $queryString = base64_decode($queryString);
    $result = [];
    parse_str($queryString, $result);
    return $result;
}


switch ($ReqM) {
    case 'GET':
        if (isset($_GET["action"]) and !is_null($_GET["action"])) {

            switch ($_GET["action"]) {
                case 'Brands':
                    if (isset($_GET["id"]) and !is_null($_GET["id"])) {
                        echo json_encode($brandRep->find($_GET["id"]));
                    } else {
                        echo json_encode($brandRep->findAll());
                    }
                    break;
                
                case 'Stocks':
                    if (isset($_GET["id"]) and !is_null($_GET["id"])) {
                        echo json_encode($stockRep->find($_GET["id"]));
                    } else {
                        echo json_encode($stockRep->findAll());
                    }
                    break;

                case 'Stores':
                    if (isset($_GET["id"]) and !is_null($_GET["id"])) {
                        echo json_encode($storeRep->find($_GET["id"]));
                    } else {
                        echo json_encode($storeRep->findAll());
                    }
                    break;

                case 'Categories':
                    if (isset($_GET["id"]) and !is_null($_GET["id"])) {
                        echo json_encode($catRep->find($_GET["id"]));
                    } else {
                        echo json_encode($catRep->findAll());
                    }
                    break;

                case 'Products':
                    if (isset($_GET["id"]) and !is_null($_GET["id"])) {
                        echo json_encode($proRep->find($_GET["id"]));
                    } else {
                        echo json_encode($proRep->findAll());
                    }
                    break;

                case 'Employees':
                    if (isset($_GET["id"]) and !is_null($_GET["id"])) {
                        if (isset($_GET["key"]) and !is_null($_GET["key"])) {
                            echo json_encode($empRep->find($_GET["id"]));
                        }
                        else {
                            echo json_encode("error");
                        }
                    } else {
                        if (isset($_GET["key"]) and !is_null($_GET["key"])) {
                            echo json_encode($empRep->findAll());
                        }
                        else {
                            echo json_encode("error");
                        }
                    }
                    break;
                
                case 'Auth':
                    if (isset($_GET["login"]) and !is_null($_GET["login"]) and isset($_GET["password"]) and !is_null($_GET["password"])) {
                        $emp = $empRep->findOneBy(["employeeEmail" => $_GET["login"]]);
                        if (!is_null($emp) and $emp->getEmployeePassword() == $_GET["password"]) {
                            echo json_encode(["email" => $emp->getEmployeeEmail(), "password" => openssl_encrypt($emp->getEmployeePassword(), "AES-128-CBC", "neHjv@bNB6Vv48jD", 0, "9119c716ff82e145"), "role" => $emp->getEmployeeRole(), "id" => $emp->getEmployeeId(), "store" => $emp->getStore()->getStoreId(), "key" => "e8f1997c763"]);
                        } else {
                            echo json_encode("error");
                        }
                    } else {
                        echo json_encode("error");
                    }
                    break;
                
                case 'AuthCheck':
                    if (isset($_GET["login"]) and !is_null($_GET["login"]) and isset($_GET["password"]) and !is_null($_GET["password"]) and isset($_GET["role"]) and !is_null($_GET["role"])) {
                        $emp = $empRep->findOneBy(["employeeEmail" => $_GET["login"]]);
                        $pwd = openssl_decrypt(base64_decode($_GET["password"]), "AES-128-CBC", "neHjv@bNB6Vv48jD", 0, "9119c716ff82e145");
                        if (!is_null($emp) and $emp->getEmployeePassword() == $pwd and $emp->getEmployeeRole() == $_GET["role"]) {
                            echo json_encode("success");
                        } else {
                            echo json_encode("error");
                        }
                    } else {
                        echo json_encode("error");
                    }
                    break;

            }

        }
        else {
            echo json_encode("error"); // include documentation here
        }
        break;

    case 'POST':
        $Post = queryStringToArray(file_get_contents("php://input"));


        if (isset($Post["key"]) and $Post["key"]=="e8f1997c763" and isset($Post["action"]) and !is_null($Post["action"])) {
            switch ($Post["action"]) {
                case 'Brands':
                    if (isset($Post["name"]) and !is_null($Post["name"])) {
                        $br = new Brands();
                        $br->setBrandName($Post["name"]);
                        $entityManager->persist($br);
                        $entityManager->flush();
                        echo json_encode("success");
                    }
                    else {
                        echo json_encode("error");
                    }
                    break;
                
                case 'Stocks':

                    $field_missing = false;
                    $fields = ["quantity", "store", "product"];

                    foreach ($fields as $value) {
                        if (!array_key_exists($value, $Post) or is_null($Post[$value]) or $Post[$value]=="") {
                            $field_missing = true;
                            break;
                        }
                    }

                    if ($field_missing) {
                        echo json_encode("error");
                        break;
                    } else {
                        $stk = new Stocks();
                        $stk->setQuantity($Post["quantity"]);
                        $stk->setStore(Id2Instance("store", $Post["store"], $proRep, $brandRep, $storeRep, $catRep));
                        $stk->setProduct(Id2Instance("product", $Post["product"], $proRep, $brandRep, $storeRep, $catRep));
                        $entityManager->persist($stk);
                        $entityManager->flush();
                        echo json_encode("success");
                    }

                    break;

                case 'Stores':

                    $field_missing = false;
                    $fields = ["name", "phone", "email", "street", "city", "state", "zipcode"];

                    foreach ($fields as $value) {
                        if (!array_key_exists($value, $Post) or is_null($Post[$value]) or $Post[$value]=="") {
                            $field_missing = true;
                            break;
                        }
                    }

                    if ($field_missing) {
                        echo json_encode("error");
                        break;
                    } else {
                        $str = new Stores();
                        $str->setStoreName($Post["name"]);
                        $str->setPhone($Post["phone"]);
                        $str->setEmail($Post["email"]);
                        $str->setStreet($Post["street"]);
                        $str->setCity($Post["city"]);
                        $str->setState($Post["state"]);
                        $str->setZipCode($Post["zipcode"]);
                        $entityManager->persist($str);
                        $entityManager->flush();
                        echo json_encode("success");
                    }

                    break;

                case 'Categories':
   
                    if (isset($Post["name"]) and !is_null($Post["name"])) {
                        $cat = new Categories();
                        $cat->setCategoryName($Post["name"]);
                        $entityManager->persist($cat);
                        $entityManager->flush();
                        echo json_encode("success");
                    }
                    else {
                        echo json_encode("error");
                    }
                    break;

                case 'Products':

                    $field_missing = false;
                    $fields = ["name", "brand", "category", "year", "price"];

                    foreach ($fields as $value) {
                        if (!array_key_exists($value, $Post) or is_null($Post[$value]) or $Post[$value]=="") {
                            $field_missing = true;
                            break;
                        }
                    }

                    if ($field_missing) {
                        echo json_encode("error");
                        break;
                    } else {
                        $pr = new Products();
                        $pr->setProductName($Post["name"]);
                        $pr->setBrandId(Id2Instance("brand", $Post["brand"], $proRep, $brandRep, $storeRep, $catRep));
                        $pr->setCategory(Id2Instance("category", $Post["category"], $proRep, $brandRep, $storeRep, $catRep));
                        $pr->setModelYear($Post["year"]);
                        $pr->setListPrice($Post["price"]);
                        $entityManager->persist($pr);
                        $entityManager->flush();
                        echo json_encode("success");
                    }
                    break;

                case 'Employees':

                    $field_missing = false;
                    $fields = ["name", "email", "password", "role", "store"];

                    foreach ($fields as $value) {
                        if (!array_key_exists($value, $Post) or is_null($Post[$value]) or $Post[$value]=="") {
                            $field_missing = true;
                            break;
                        }
                    }

                    if ($field_missing) {
                        echo json_encode("error");
                        break;
                    } else {
                        $emp = new Employees();
                        $emp->setEmployeeName($Post["name"]);
                        $emp->setEmployeeEmail($Post["email"]);
                        $emp->setEmployeePassword($Post["password"]);
                        $emp->setEmployeeRole($Post["role"]);
                        $emp->setStore(Id2Instance("store", $Post["store"], $proRep, $brandRep, $storeRep, $catRep));
                        $entityManager->persist($emp);
                        $entityManager->flush();
                        echo json_encode("success");
                    }

                    break;
            }
        }
        else {
            echo json_encode("error");
        }
        break;

    case 'PUT':
        $Put = queryStringToArray(file_get_contents("php://input"));

        if (isset($Put["key"]) and $Put["key"]=="e8f1997c763" and isset($Put["action"]) and !is_null($Put["action"])) {
            switch ($Put["action"]) {
                case 'Brands':

                    if (isset($Put["id"]) and !is_null($Put["id"])) {
                        $br = $brandRep->find($Put["id"]);
                        if (!is_null($br)) {
                            if (isset($Put["name"]) and !is_null($Put["name"])) {
                                $br->setBrandName($Put["name"]);
                                $entityManager->flush($br);
                                echo json_encode("Data sucessfully inserted for entity with ID ". $Put["id"]);
                            }
                        }
                        else {
                            echo json_encode("error");
                        }
                    }
                    else {
                        echo json_encode("error");
                    }
                    break;
                
                case 'Stocks':

                    // name => func name
                    $fields = ["quantity" => "setQuantity", "store" => "setStore", "product" => "setProduct"];


                    if (isset($Put["id"]) and !is_null($Put["id"])) {
                        $stk = $stockRep->find($Put["id"]);
                        if (!is_null($stk)) {
                            foreach ($fields as $key=>$value) {
                                if (array_key_exists($key, $Put) and !is_null($Put[$key]) and $Put[$key]!="") {
                                    $v = Id2Instance($key, $Put[$key], $proRep, $brandRep, $storeRep, $catRep);
                                    call_user_func([$stk, $value], $v);
                                }
                            }
                            $entityManager->flush($stk);
                            echo json_encode("Data sucessfully inserted for entity with ID ". $Put["id"]);
                        }
                    }
                    else {
                        echo json_encode("error");
                    }
                    break;

                case 'Stores':

                    $fields = ["name" => "setStoreName", "phone" => "setPhone", "email" => "setEmail", "street" => "setStreet", "city" => "setCity", "state" => "setState", "zipcode" => "setZipCode"];


                    if (isset($Put["id"]) and !is_null($Put["id"])) {
                        $str = $storeRep->find($Put["id"]);
                        if (!is_null($str)) {
                            foreach ($fields as $key=>$value) {
                                if (array_key_exists($key, $Put) and !is_null($Put[$key]) and $Put[$key]!="") {
                                    $v = Id2Instance($key, $Put[$key], $proRep, $brandRep, $storeRep, $catRep);
                                    call_user_func([$str, $value], $v);
                                }
                            }
                            $entityManager->flush($str);
                            echo json_encode("Data sucessfully inserted for entity with ID ". $Put["id"]);
                        }
                        else {
                            echo json_encode("error");
                        }
                    }
                    else {
                        echo json_encode("error");
                    }
                    break;

                case 'Categories':
   

                    if (isset($Put["id"]) and !is_null($Put["id"])) {
                        $cat = $catRep->find($Put["id"]);
                        if (!is_null($cat)) {
                            if (isset($Put["name"]) and !is_null($Put["name"])) {
                                $cat->setCategoryName($Put["name"]);
                                $entityManager->flush($cat);
                                echo json_encode("Data sucessfully inserted for entity with ID ". $Put["id"]);
                            }
                        }
                        else {
                            echo json_encode("error");
                        }
                    }
                    else {
                        echo json_encode("error");
                    }
                    break;

                case 'Products':

                    $fields = ["name" => "setProductName", "brand" => "setBrandId", "category" => "setCategory", "year" => "setModelYear", "price" => "setListPrice"];


                    if (isset($Put["id"]) and !is_null($Put["id"])) {
                        $pr = $proRep->find($Put["id"]);
                        if (!is_null($pr)) {
                            foreach ($fields as $key=>$value) {
                                if (array_key_exists($key, $Put) and !is_null($Put[$key]) and $Put[$key]!="") {
                                    $v = Id2Instance($key, $Put[$key], $proRep, $brandRep, $storeRep, $catRep);
                                    call_user_func([$pr, $value], $v);
                                }
                            }
                            $entityManager->flush($pr);
                            echo json_encode("Data sucessfully inserted for entity with ID ". $Put["id"]);
                        }
                        else {
                            echo json_encode("error");
                        }
                    }
                    else {
                        echo json_encode("error");
                    }
                    break;

                case 'Employees':

                    $fields = ["name" => "setEmployeeName", "email" => "setEmployeeEmail", "password" => "setEmployeePassword", "role" => "setEmployeeRole", "store" => "setStore"];

                    if (isset($Put["id"]) and !is_null($Put["id"])) {
                        $emp = $empRep->find($Put["id"]);
                        if (!is_null($emp)) {
                            foreach ($fields as $key=>$value) {
                                if (array_key_exists($key, $Put) and !is_null($Put[$key]) and $Put[$key]!="") {
                                    $v = Id2Instance($key, $Put[$key], $proRep, $brandRep, $storeRep, $catRep);
                                    call_user_func([$emp, $value], $v);
                                }
                            }
                            $entityManager->flush($emp);
                            echo json_encode("Data sucessfully inserted for entity with ID ". $Put["id"]);
                        }
                        else {
                            echo json_encode("error");
                        }
                    }
                    else {
                        echo json_encode("error");
                    }
                    break;
            }
        }
        else {
            echo json_encode("error");
        }
        break;

    case 'DELETE':
        $Delete = queryStringToArray(file_get_contents("php://input"));

        if (isset($Delete["key"]) and $Delete["key"]=="e8f1997c763" and isset($Delete["action"]) and !is_null($Delete["action"])) {
            switch ($Delete["action"]) {
                case 'Brands': // depends on products

                    if (isset($Delete["id"]) and !is_null($Delete["id"])) {
                        $br = $brandRep->find($Delete["id"]);

                        $noConstraint = true;

                        foreach ($proRep->findAll() as $key => $value) {
                            if ($value->getBrandId() == $Delete["id"]) {
                                $noConstraint = false;
                                break;
                            }
                        }

                        if (!is_null($br) and $noConstraint) {
                            $entityManager->remove($br);
                            $entityManager->flush();
                            echo json_encode("Element successfully removed : ID ". $Delete["id"]);
                        }
                        else {
                            echo json_encode("error");
                        }
                    }
                    else {
                        echo json_encode("error");
                    }
                    break;
                
                case 'Stocks': // no constraint

                    if (isset($Delete["id"]) and !is_null($Delete["id"])) {
                        $stk = $stockRep->find($Delete["id"]);
                        if (!is_null($stk)) {
                            $entityManager->remove($stk);
                            $entityManager->flush();
                            echo json_encode("Element successfully removed : ID ". $Delete["id"]);
                        }
                        else {
                            echo json_encode("error");
                        }
                    }
                    else {
                        echo json_encode("error");
                    }
                    break;

                case 'Stores': // depends on employees and stocks

                    if (isset($Delete["id"]) and !is_null($Delete["id"])) {
                        $str = $storeRep->find($Delete["id"]);

                        $noConstraint = true;

                        foreach ($empRep->findAll() as $key => $value) {
                            if ($value->getStore() == $Delete["id"]) {
                                $noConstraint = false;
                                break;
                            }
                        }
                        if ($noConstraint) {
                            foreach ($stockRep->findAll() as $key => $value) {
                                if ($value->getStore() == $Delete["id"]) {
                                    $noConstraint = false;
                                    break;
                                }
                            }
                        }

                        if (!is_null($str) and $noConstraint) {
                            $entityManager->remove($str);
                            $entityManager->flush();
                            echo json_encode("Element successfully removed : ID ". $Delete["id"]);
                        }
                        else {
                            echo json_encode("error");
                        }
                    }
                    else {
                        echo json_encode("error");
                    }
                    break;

                case 'Categories': // depends on products
   
                    if (isset($Delete["id"]) and !is_null($Delete["id"])) {
                        $cat = $catRep->find($Delete["id"]);

                        $noConstraint = true;

                        foreach ($proRep->findAll() as $key => $value) {
                            if ($value->getCategory() == $Delete["id"]) {
                                $noConstraint = false;
                                break;
                            }
                        }

                        if (!is_null($cat) and $noConstraint) {
                            $entityManager->remove($cat);
                            $entityManager->flush();
                            echo json_encode("Element successfully removed : ID ". $Delete["id"]);
                        }
                        else {
                            echo json_encode("error");
                        }
                    }
                    else {
                        echo json_encode("error");
                    }
                    break;

                case 'Products': // depends on stocks

                    if (isset($Delete["id"]) and !is_null($Delete["id"])) {
                        $pr = $proRep->find($Delete["id"]);
  
                        $noConstraint = true;

                        foreach ($stockRep->findAll() as $key => $value) {
                            if ($value->getProduct() == $Delete["id"]) {
                                $noConstraint = false;
                                break;
                            }
                        }

                        if (!is_null($pr)) {
                            $entityManager->remove($pr);
                            $entityManager->flush();
                            echo json_encode("Element successfully removed : ID ". $Delete["id"]);
                        }
                        else {
                            echo json_encode("error");
                        }
                    }
                    else {
                        echo json_encode("error");
                    }
                    break;

                case 'Employees': // no constraint

                    if (isset($Delete["id"]) and !is_null($Delete["id"])) {
                        $emp = $empRep->find($Delete["id"]);
                        if (!is_null($emp)) {
                            $entityManager->remove($emp);
                            $entityManager->flush();
                            echo json_encode("Element successfully removed : ID ". $Delete["id"]);
                        }
                        else {
                            echo json_encode("error");
                        }
                    }
                    else {
                        echo json_encode("error");
                    }
                    break;
            }
        }
        else {
            echo json_encode("error");
        }
        break;

    default:
        # code...
        break;
}

?>