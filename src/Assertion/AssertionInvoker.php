<?php

namespace Leonc\RouteBinder\Assertion;

use Leonc\RouteBinder\Builders\StrategyBuilder;

class AssertionInvoker
{
    public function __construct($model, $strategy){
        $this->assertionBuilder = new AssertionBuilder($model);
        $this->strategy = StrategyBuilder::get(null);
        $this->customFailMessage = null;
        $this->isStrategyPersistant = false;
        $this->isFailMessagePersistant = false;
    }
    
    public function __call($method, $args){
        $result = $this->invoke($method, ...$args);
        if(!$result->passes()){
            $this->strategy->fail(
                $result->getFailMessage(), $result->getModelName()
            );
        }
        else{
            $this->failMessage()->strategy();
            return $this;
        }
    }

    public function bind(){
        return $this->strategy->bind($this->assertionBuilder->getModel());
    }

    private function invoke($name, ...$params){
        $this->assertionBuilder->setFailMessage($this->customFailMessage);
        $result = $this->assertionBuilder->{$name}(...$params);
        return $result;
    }

    public function failMessage($message = null){
        if(!$this->isFailMessagePersistant){
            $this->customFailMessage = $message;
        }
        return $this;
    }

    public function persistFailMessage($message){
        $this->customFailMessage = $message;
        $this->isFailMessagePersistant = true;
        return $this;
    }

    public function strategy($class = null){
        if(!$this->isStrategyPersistant){
            $this->strategy = StrategyBuilder::get($class);
        }
        return $this;
    }

    public function persistStrategy($class){
        $this->strategy = StrategyBuilder::get($class);
        $this->isStrategyPersistant = true;
        return $this;
    }

}