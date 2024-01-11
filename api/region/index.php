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

/*

DROP DATABASE seguimiento_estudiante;
DROP USER seguimiento;

CREATE DATABASE seguimiento_estudiante;
-- CREATE USER 'seguimiento' IDENTIFIED BY "L+G0)!4hQ55s+Z^UgIIH4AVP3+(Lex%u8$PmlVcPLdX@S64$R3yWjE^hYaXp";
-- GRANT ALL PRIVILEGES ON seguimiento_estudiante.* TO 'seguimiento'@'localhost';
-- GRANT ALL PRIVILEGES ON seguimiento_estudiante.* TO 'seguimiento'@'localhost' WITH GRANT OPTION;
FLUSH PRIVILEGES;

show grants;

ALTER USER 'root'@'localhost' IDENTIFIED BY 'M4rc3l1t4.123.abcD';
FLUSH PRIVILEGES;

-- tablas bd -- 
use seguimiento_estudiante;

CREATE TABLE region(
	id INT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    numero INT NOT NULL UNIQUE,
    romano VARCHAR(10) NOT NULL UNIQUE,
    activo BOOLEAN NOT NULL DEFAULT TRUE
);

CREATE TABLE comuna(
	id INT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    region_id INT NOT NULL,
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    FOREIGN KEY (region_id) REFERENCES region(id)
);

CREATE TABLE establecimiento(
	id INT PRIMARY KEY,
    rbd VARCHAR(50) NOT NULL UNIQUE,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    direccion_calle VARCHAR(100) NOT NULL,
    comuna_id INT NOT NULL,
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    FOREIGN KEY (comuna_id) REFERENCES comuna(id)
);

CREATE TABLE plataforma(
	id INT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    activo BOOLEAN NOT NULL DEFAULT TRUE
);

CREATE TABLE estudiante(
	id INT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    rut VARCHAR(13) NOT NULL,
    fecha_nacimiento DATE,
    email VARCHAR(100),
    fono VARCHAR(20),
    titulo_pregrado VARCHAR(200),
    titulo_pregrado_universidad VARCHAR(200),
    titulo_pregrado_mencion VARCHAR(200),
    activo BOOLEAN NOT NULL DEFAULT TRUE
);

CREATE TABLE estudiante_plataforma(
	id INT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    estudiante_id INT NOT NULL,
    plataforma_id INT NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    FOREIGN KEY (estudiante_id) REFERENCES estudiante(id),
    FOREIGN KEY (plataforma_id) REFERENCES plataforma(id)
);

CREATE TABLE estudiante_establecimiento(
	id INT PRIMARY KEY,
    estudiante_id INT NOT NULL,
    establecimiento_id INT NOT NULL,
    fecha_ingreso TIMESTAMP,
    fecha_salida TIMESTAMP,
    FOREIGN KEY (estudiante_id) REFERENCES estudiante(id),
    FOREIGN KEY (establecimiento_id) REFERENCES establecimiento(id)
);

CREATE TABLE estudiante_direccion(
	id INT PRIMARY KEY,
    estudiante_id INT NOT NULL,
    direccion_calle VARCHAR(100) NOT NULL,
    comuna_id INT NOT NULL,
    fecha_ingreso TIMESTAMP,
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    FOREIGN KEY (estudiante_id) REFERENCES estudiante(id),
    FOREIGN KEY (comuna_id) REFERENCES comuna(id)
);

*/