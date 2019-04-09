<?php

namespace Leonc\RouteBinder\Builders;

class ModelBuilder
{
    public function __construct($class, $key, $strategy, $relations){
        $this->class = $class;
        $this->key = $key;
        $this->strategy = $strategy;
        $this->relations = $relations;
    }

    public function getModelOrFail(){
        $model = $this->strategy->getModel(new $this->class, $this->key, $this->relations);
        
        if($this->strategy->exists($model)){
            return $model;
        }
        else{
            $model_name = (new \ReflectionClass($this->class))->getShortName();
            $message = $model_name. " not found";
            $this->strategy->fail($message, $model_name);
        }
    }

}