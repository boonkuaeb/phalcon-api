<?php
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Message;
use Phalcon\Mvc\Model\Validator\PresenceOf;
use Phalcon\Mvc\Model\Validator\Regex;
use Phalcon\Mvc\Model\Validator\Uniqueness;

class Cars extends Model
{

    public $owner_name;
    public $req_date;
    public $license_plate_no;
    public $engine_no;
    public $tax_payment;
    public $car_model;
    public $car_model_year;
    public $seating_capacity;
    public $horse_power;

    public function validation()
    {
        $this->validate(
            new PresenceOf(
                array(
                    'field' => 'license_plate_no',
                    'message' => 'The license plate no is required.'
                )
            )
        );

        $this->validate(
            new PresenceOf(
                array(
                    'field' => 'engine_no',
                    'message' => 'The engine no is required.'
                )
            )
        );

        $this->validate(
            new PresenceOf(
                array(
                    'field' => 'owner_name',
                    'message' => 'The owner name is required.'
                )
            )
        );

        // license_plate_number uniqueness check
        $this->validate(
            new Uniqueness(
                array(
                    "field" => "license_plate_no",
                    "message" => "The license plate no is already used."
                )
            )
        );

        // engine number's uniqueness check
        $this->validate(
            new Uniqueness(
                array(
                    "field" => "engine_no",
                    "message" => "The engine number is already used."
                )
            )
        );


        // Regular Expression to verify license_plate_number's pattern
        $this->validate(
            new Regex(
                array(
                    "field" => "license_plate_no",
                    "pattern" => "/^[A-Z]{3}-[0-9]{3}$/",
                    "message" => "Invalid license plate number."
                )
            )
        );


        // Custom Validation
        if ($this->car_model_year < 0) {
            $this->appendMessage(new Message("Car's model year can not be zero."));
        }

        if ($this->validationHasFailed() == true) {
            return false;
        }

    }

}
