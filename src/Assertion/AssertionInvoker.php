<?php

namespace Leonc\RouteBinder\Assertion;

use Leonc\RouteBinder\Strategy\BaseStrategy;
use Illuminate\Database\Eloquent\Model;

class AssertionInvoker
{
    public function __construct(Model $model, BaseStrategy $strategy){
        $this->assertionBuilder = new AssertionBuilder($model);
        $this->strategy = $strategy;
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

    public function strategy(string $class = BaseStrategy::class){
        if(!$this->isStrategyPersistant){
            $this->setStrategyOrThrow($class);
        }
        return $this;
    }

    public function persistStrategy(string $class = BaseStrategy::class){
        $this->setStrategyOrThrow($class);
        $this->isStrategyPersistant = true;
        return $this;
    }

    private function setStrategyOrThrow(string $class){
        $strategyInstance = new $class;
        if($strategyInstance instanceof BaseStrategy){
            $this->strategy = new $class;
        }
        else{
            throw 'Strategy has to extend Leonc\RouteBinder\Strategy\BaseStrategy!';
        }
    }

}