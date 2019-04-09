<?php

namespace Leonc\RouteBinder\Assertion;

class AssertionResult
{
    public function __construct($passes, $message, $modelName){
        $this->passes = $passes;
        $this->message = $message;
        $this->modelName = $modelName;
    }
    
    public function passes(){
        return $this->passes;
    }
    
    public function getFailMessage(){
        return $this->message;
    }

    public function getModelName(){
        return $this->modelName;
    }

}