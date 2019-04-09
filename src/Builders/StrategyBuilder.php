<?php

namespace Leonc\RouteBinder\Builders;

use Leonc\RouteBinder\Strategy\BaseStrategy;

class StrategyBuilder
{
    public static function get($class){
        if(is_null($class)) $class = BaseStrategy::class;   
        return self::validate(new $class);
    }

    private static function validate($instance){
        if(!$instance instanceof BaseStrategy){
            throw new \Exception("Strategy has to implement StrategyInterface !");
        }

        return $instance;
    }
}