<?php
use Phalcon\Http\Response;

$app->get('/api/cars', function () use ($app) {
    $phql = "SELECT * FROM Cars ORDER BY id DESC";
    $cars = $app->modelsManager->executeQuery($phql);


    $response = new Response();
    if ($cars == false) {
        $response->setJsonContent(
            array(
                'status' => 'NOT-FOUND'
            )
        );
    } else {

        $data = array();
        foreach ($cars as $car) {
            $data[] = array(
                'id' => $car->id,
                'owner_name' => $car->owner_name,
                'reg_date' => $car->reg_date,
                'license_plate_no' => $car->license_plate_no,
                'engine_no' => $car->engine_no,
                'tax_payment' => $car->tax_payment,
                'car_model' => $car->car_model,
                'car_model_year' => $car->car_model_year,
                'seating_capacity' => $car->seating_capacity,
                'horse_power' => $car->horse_power
            );
        }
        $response->setJsonContent(array(
            'status' => 'FOUND',
            'data' => $data
        ));
    }

    return $response;

});

$app->get('/api/cars/search/{license_plate_no}', function ($license_plate_no) use ($app) {
    $phql = "SELECT * FROM Cars WHERE license_plate_no = :license_plate_no:";
    $value = array('license_plate_no' => $license_plate_no);

    $car = $app->modelsManager->executeQuery($phql, $value)->getFirst();

    // second
    $response = new Response();
    if ($car == false) {
        $response->setJsonContent(
            array(
                'status' => 'NOT-FOUND'
            )
        );
    } else {
        $response->setJsonContent(
            array(
                'status' => 'FOUND',
                'data' => array(
                    'id' => $car->id,
                    'owner_name' => $car->owner_name,
                    'reg_date' => $car->reg_date,
                    'license_plate_no' => $car->license_plate_no,
                    'engine_no' => $car->engine_no,
                    'tax_payment' => $car->tax_payment,
                    'car_model' => $car->car_model,
                    'car_model_year' => $car->car_model_year,
                    'seating_capacity' => $car->seating_capacity,
                    'horse_power' => $car->horse_power
                )
            )
        );
    }

    // third
    return $response;


});


// Retrieves cars based on primary key ($id)
$app->get('/api/cars/{id:[0-9]+}', function ($id) use ($app) {

    // first
    $phql = "SELECT * FROM Cars WHERE id = :id: ";
    $values = array('id' => $id);
    $car = $app->modelsManager->executeQuery($phql, $values)->getFirst();
    // second
    $response = new Response();
    if ($car == false) {
        $response->setJsonContent(
            array(
                'status' => 'NOT-FOUND'
            )
        );
    } else {
        $response->setJsonContent(
            array(
                'status' => 'FOUND',
                'data' => array(
                    'id' => $car->id,
                    'owner_name' => $car->owner_name,
                    'reg_date' => $car->reg_date,
                    'license_plate_no' => $car->license_plate_no,
                    'engine_no' => $car->engine_no,
                    'tax_payment' => $car->tax_payment,
                    'car_model' => $car->car_model,
                    'car_model_year' => $car->car_model_year,
                    'seating_capacity' => $car->seating_capacity,
                    'horse_power' => $car->horse_power
                )
            )
        );
    }

    // third
    return $response;
});

$app->post('/api/cars', function () use ($app) {
    // first
    $phql = "INSERT INTO Cars (owner_name, reg_date, license_plate_no, engine_no, tax_payment, car_model, car_model_year, seating_capacity, horse_power) VALUES (:owner_name:, :reg_date:, :license_plate_no:, :engine_no:, :tax_payment:, :car_model:, :car_model_year:, :seating_capacity:, :horse_power:)";
    // second
    $car = $app->request->getJsonRawBody();

    $values = array(
        'owner_name' => $car->owner_name,
        'reg_date' => $car->reg_date,
        'license_plate_no' => $car->license_plate_no,
        'engine_no' => $car->engine_no,
        'tax_payment' => $car->tax_payment,
        'car_model' => $car->car_model,
        'car_model_year' => $car->car_model_year,
        'seating_capacity' => $car->seating_capacity,
        'horse_power' => $car->horse_power
    );
    $results = $app->modelsManager->executeQuery($phql, $values);
    // third
    $response = new Response();
    if ($results->success() == true) {
        $response->setStatusCode(201, "Created");
        $car->id = $results->getModel()->id;
        $response->setJsonContent(
            array(
                'status' => 'OK',
                'data' => $car
            )
        );
    } else {
        $response->setStatusCode(409, "Conflict");
        $errors = array();
        foreach ($results->getMessages() as $message) {
            $errors[] = $message->getMessage();
        }
        $response->setJsonContent(
            array(
                'status' => 'ERROR',
                'messages' => $errors
            )
        );
    }

    return $response;
});

// Updates car based on primary key ($id)
$app->put('/api/cars/{id:[0-9]+}', function ($id) use ($app) {
    // first
    $phql = "UPDATE Cars SET owner_name = :owner_name:, reg_date = :reg_date:, license_plate_no = :license_plate_no:, engine_no = :engine_no:, tax_payment = :tax_payment:, car_model = :car_model:, car_model_year = :car_model_year:, seating_capacity = :seating_capacity:, horse_power = :horse_power: WHERE id = :id: ";
    // second
    $updatedCarValues = $app->request->getJsonRawBody();
    $values = array(
        'id' => $id,
        'owner_name' => $updatedCarValues->owner_name,
        'reg_date' => $updatedCarValues->reg_date,
        'license_plate_no' => $updatedCarValues->license_plate_no,
        'engine_no' => $updatedCarValues->engine_no,
        'tax_payment' => $updatedCarValues->tax_payment,
        'car_model' => $updatedCarValues->car_model,
        'car_model_year' => $updatedCarValues->car_model_year,
        'seating_capacity' => $updatedCarValues->seating_capacity,
        'horse_power' => $updatedCarValues->horse_power
    );
    $results = $app->modelsManager->executeQuery($phql, $values);
    // third
    $response = new Response();
    if ($results->success() == true) {
        $response->setStatusCode(200, "OK");
        $response->setJsonContent(
            array(
                'status' => 'OK'
            )
        );
    } else {
        $response->setStatusCode(409, "Conflict");
        $errors = array();
        foreach ($results->getMessages() as $message) {
            $errors[] = $message->getMessage();
        }
        $response->setJsonContent(
            array(
                'status' => 'ERROR',
                'messages' => $errors
            )
        );
    }

    return $response;
});

// Deletes car based on primary key ($id)
$app->delete('/api/cars/{id:[0-9]+}', function ($id) use ($app) {
    // first
    $phql = "DELETE FROM Cars WHERE id = :id: ";
    // second
    $values = array(
        'id' => $id
    );
    $results = $app->modelsManager->executeQuery($phql, $values);
    // third
    $response = new Response();
    if ($results->success() == true) {
        $response->setStatusCode(200, "OK");
        $response->setJsonContent(
            array(
                'status' => 'OK'
            )
        );
    } else {
        $response->setStatusCode(409, "Conflict");
        $errors = array();
        foreach ($results->getMessages() as $message) {
            $errors[] = $message->getMessage();
        }
        $response->setJsonContent(
            array(
                'status' => 'ERROR',
                'messages' => $errors
            )
        );
    }

    return $response;
});
