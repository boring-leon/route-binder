<?php

namespace Leonc\RouteBinder;

use Leonc\RouteBinder\Assertion\AssertionInvoker;
use Leonc\RouteBinder\Builders\ModelBuilder;
use Leonc\RouteBinder\Builders\StrategyBuilder;

class Binder
{ 
    private $invoker;
    
    protected function __construct($modelClass, $param, $strategy, $relations){
        $this->modelClass = $modelClass;
        $this->param = $param;
        $this->relations = $relations;
        $this->setStrategy($strategy);
        $this->setModel();
        $this->setInvoker();
    }

    public static function build($modelClass, $param, $config = []){        
        if(!array_key_exists('strategy', $config)) $config['strategy'] = null;
        if(!array_key_exists('relations', $config)) $config['relations'] = [];

        $instance = new self($modelClass, $param, $config['strategy'], $config['relations'] );
        return $instance->getInvoker();
    }

    public function getInvoker(){
        return $this->invoker;
    }

    private function setStrategy($strategy){
        $this->strategy = StrategyBuilder::get($strategy);
    }
    
    private function setInvoker(){
        $this->invoker = new AssertionInvoker($this->model, $this->strategy);
    }
    
    private function setModel(){
        $builder = new ModelBuilder($this->modelClass, $this->param, $this->strategy, $this->relations);
        $this->model = $builder->getModelOrFail();
    }
}