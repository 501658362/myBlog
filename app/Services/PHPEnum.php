<?php
/**
 * @brief PHP Enum
 * @author IvanHuang <huanghe@ubtour.com>
 * @since 2015-12-29
 * @copyright Copyright (c) www.ubtour.com
 */
namespace App\Services;

use UnexpectedValueException ;

abstract class PHPEnum {

    /**
     * @var array
     */
    private static $_constants = [] ;

    /**
     * Creates new PHPEnum object. If child class overrides __construct(),
     * it is required to call parent::__construct() in order for this
     * class to work as expected.
     * @param int $code
     */
    public function __construct($code) {
        $class = get_class($this) ;
        if (!array_key_exists($class, self::$_constants)) {
            self::_populateConstants() ;
        }
        if (!in_array($code, self::$_constants[$class], true)) {
            throw new UnexpectedValueException("Value is not in enum " . $class) ;
        }
    }

    private function _populateConstants() {
        $class = get_class($this) ;
        $r = new \ReflectionClass($class) ;
        $_constants = $r->getConstants() ;
        self::$_constants = [$class => $_constants] ;
    }
}
