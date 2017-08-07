<?php
namespace App\Services;

class HttpResponseType extends PHPEnum {

    const UBRESPONSE = 1;
    const CUSTOM     = 2;
    private $type;
    public static $values
        = [
            self::UBRESPONSE => 1,
            self::CUSTOM     => 2
        ];

    public function __construct($code = HttpResponseType::UBRESPONSE) {
        parent::__construct($code);
        $this->type = $code;
        return $this;
    }

    public function getType() {
        return $this->type;
    }
}