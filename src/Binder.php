<?php

namespace Leonc\RouteBinder;

use Leonc\RouteBinder\Assertion\AssertionInvoker;
use Leonc\RouteBinder\Builders\ModelBuilder;
use Leonc\RouteBinder\Strategy\BaseStrategy;

class Binder
{ 
    private $invoker;
    
    protected function __construct(string $modelClass, $param, BaseStrategy $strategy, array $relations){
        $this->modelClass = $modelClass;
        $this->param = $param;
        $this->relations = $relations;
        $this->strategy = $strategy;
        $this->setModel();
        $this->setInvoker();
    }

    public static function build(string $modelClass, $param, array $relations = []){        
        $instance = new self($modelClass, $param, new BaseStrategy, $relations );
        return $instance->getInvoker();
    }

    public function getInvoker(){
        return $this->invoker;
    }
    
    private function setInvoker(){
        $this->invoker = new AssertionInvoker($this->model, $this->strategy);
    }
    
    private function setModel(){
        $builder = new ModelBuilder($this->modelClass, $this->param, $this->strategy, $this->relations);
        $this->model = $builder->getModelOrFail();
    }
}