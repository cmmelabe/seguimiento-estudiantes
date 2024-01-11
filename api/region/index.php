<?php

$tokenDyP = 'tokenDyP';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    header('Content-Type: application/json');
    try {
        $header = getallheaders()['Authorization'];
        $tokenTipo = explode(' ', $header)[0];
        $tokenValor = explode(' ', $header)[1];
        if ($tokenTipo === 'Bearer' && $tokenValor === $tokenDyP) {
            if (isset($_GET) && count($_GET) > 0) {
                include_once '../../controller/conexion.php';
                include_once '../../controller/controllerRegion.php';
                $control = new ControlRegion();
                //usando metodo get
                if (isset($_GET['all'])) {
                    $lista = $control->getLista();
                    echo json_encode(array("http_response_code" => array("code" => 200, "message" => "OK"), "length" => count($lista), "region" => $lista));
                } else {
                    echo json_encode(array("http_response_code" => array("code" => 406, "message" => "GET No aceptable")));
                }
            } else {
                echo json_encode(array("http_response_code" => array("code" => 403, "message" => "Método Prohibido")));
            }
        } else {
            echo json_encode(array("http_response_code" => array("code" => 401, "message" => "Sin Autorización - Método Incorrecto")));
        }
    } catch (\Throwable $th) {
        echo json_encode(array("http_response_code" => array("code" => 403, "message" => "Prohibido el acceso a la Base de Datos")));
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    try {
        $header = getallheaders()['Authorization'];
        $datosBody = json_decode(file_get_contents("php://input"), true);
        $tokenTipo = explode(' ', $header)[0];
        $tokenValor = explode(' ', $header)[1];
        if ($tokenTipo === 'Bearer' && $tokenValor === $tokenDyP) {
            include_once '../../controller/conexion.php';
            include_once '../../controller/controllerRegion.php';
            $control = new ControlRegion();
            $lista = array();

            if (count($datosBody) == 7) {
                $nuevo = array(
                    "nombre" => $datosBody['name'],
                    "numero" => $datosBody['number'],
                    "romano" => $datosBody['roman_numeral']
                );
                // echo json_encode($nuevo);
                $respuestaADD = $control->add($nuevo);
                echo json_encode(array("http_response_code" => array("code" => 201, "message" => "Creado"), "qr" => $respuestaADD));
            } else {
                foreach ($datosBody as $value) {
                    if ($value['id'] == 48) {
                        $value['id'] = 16; //Ñuble
                    }
                    $nuevo = array(
                        "nombre" => $value['name'],
                        "numero" => $value['number'],
                        "romano" => $value['roman_numeral']
                    );
                    array_push($lista, $nuevo);
                }
                $respuestaADD = $control->addLista($lista);
                echo json_encode(array("http_response_code" => array("code" => 201, "message" => "Creado"), "qr" => $respuestaADD));
            }



            // echo json_encode($lista);

            // $respuestaADD = $control->addLista($lista);
            // echo json_encode($respuestaADD);

            // if (strlen($datosBody['tipo']) > 0 && count($datosBody['caracteristicas']) >= 0) {
            //     //se crea un nuevo objeto
            //     // $aux = array(
            //     //     "type" => $datosBody['tipo'],
            //     //     "features" => $datosBody['caracteristicas']
            //     // );
            //     // var_dump($aux);
            //     // $respuestaADD = $control->add($aux);
            //     // echo 'respuesta add[' . $respuestaADD . ']';
            // }
        } else {
            echo json_encode(array("http_response_code" => array("code" => 401, "message" => "Sin Autorización - Método Incorrecto")));
        }
    } catch (\Throwable $th) {
        echo json_encode(array("http_response_code" => array("code" => 403, "message" => "Prohibido el acceso a la Base de Datos")));
        echo $th;
    }
}
// // //estructura
// // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// //     header('Content-Type: application/json');
// //     try {
// //         $header = getallheaders()['Authorization'];
// //         $datosBody = json_decode(file_get_contents("php://input"), true);
// //         $tokenTipo = explode(' ', $header)[0];
// //         $tokenValor = explode(' ', $header)[1];
// //         if ($tokenTipo === 'Bearer' && $tokenValor === 'tokenGonzaloGON') {

// //         } else {
// //             echo json_encode(array("http_response_code" => array("code" => 401, "message" => "Sin Autorización - Método Incorrecto")));
// //         }
// //     } catch (\Throwable $th) {
// //         echo json_encode(array("http_response_code" => array("code" => 403, "message" => "Prohibido el acceso a la Base de Datos")));
// //     }
// // }
