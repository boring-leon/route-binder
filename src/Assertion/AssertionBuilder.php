<?php

namespace Leonc\RouteBinder\Assertion;

use Illuminate\Database\Eloquent\Model;

class AssertionBuilder
{
    private $model;

    public function __construct(Model $model){
        $this->customFailMessage = null;
        $this->model = $model;
        $this->model_name = $this->getShortName(get_class($this->model));
    }

    public function belongsTo(string $class, $val, $key = null){
        $name = $this->getShortName($class);
        $key = $this->getPrimaryKey($name, $key);
        return $this->makeResult(
            $this->model->{$key} == $val,
            " has to belong to ${name} with key {$key} equal to {$val}"
        );
    }

    public function belongsToBy(string $targetClass, string $agentClass, $val, $agentKey = null, $targetKey = null){
        $agentName = $this->getShortName($agentClass); 
        $targetName = $this->getShortName($targetClass);
        $agentKey = $this->getPrimaryKey($agentName, $agentKey);
        $targetKey = $this->getPrimaryKey($targetName, $targetKey);
        
        $agent = (new $agentClass)->find($this->getVal($agentKey));
        if(is_null($agent)){
            return $this->makeResult(
                false,
                " has to belongs to {$agentName} which belongs to ${targetName} with key {$targetKey} equal to {$val}"
            );
        }
        else{
            return $this->makeResult(
                $agent->{$targetKey} == $val,
                " has to belongs to {$agentName} which belongs to ${targetName} with key {$targetKey} equal to {$val}"
            );
        }
    }

    public function hasAttr(string $attr){
        return $this->makeResult(
            array_key_exists($attr, $this->model->toArray()),
            " has to have {$attr} attribute"
        );
    }

    public function attrEquals(string $attr, $val){
        return $this->makeResult(
            $this->getVal($attr) == $val,
            "'s {$attr} attribute has to equal {$val}"
        );
    }

    public function attrTruthy(string $attr){
        return $this->attrEquals($attr, true);
    }

    public function attrFalsy(string $attr){
        return $this->attrEquals($attr, false);
    }

    public function attrEqualsStrong(string $attr, $val){
        return $this->makeResult(
            $this->getVal($attr) === $val,
            "'s {$attr} attribute has to equal {$val}"
        );
    }

    public function attrHasLength(string $attr, $length){
        if(is_countable($this->model->{$attr})){
            return $this->makeResult(
                count( $this->getVal($attr) ) == $length,
                "'s {$attr} attribute has to have length {$length}"
            );
        }
        else if(is_numeric($this->getVal($attr))){
            return $this->makeResult(
                $this->getVal($attr) == $length,
                "'s {$attr} attribute has to equal {$length}"
            );
        }
        else{
            return $this->makeResult(false, "'s {$attr} has to be countable!" );
        }
    }

    public function attrGreaterThan(string $attr, $val){
        return $this->makeResult(
            $this->getAttrToComparison($attr) > $val,
            "'s {$attr} attribute has to be greater than {$val}"
        );
    }

    public function attrGreaterEqual(string $attr, $val){
        return $this->makeResult(
            $this->getAttrToComparison($attr) >= $val,
            "'s {$attr} attribute has to be greater or equal {$val}"
        );
    }

    public function attrLessThan(string $attr, $val){
        return $this->makeResult(
            $this->getAttrToComparison($attr) < $val,
            "'s {$attr} attribute has to be less than {$val}"
        );
    }

    public function attrLessEqual(string $attr, $val){
        return $this->makeResult(
            $this->getAttrToComparison($attr) <= $val,
            "'s {$attr} attribute has to be less or equal {$val}"
        );
    }

    public function attrBetween(string $attr, $val1, $val2){
        $attribute  = $this->getAttrToComparison($attr);
        return $this->makeResult(
            $attribute  < max($val1, $val2)  && $attribute  > min($val1, $val2),
            "'s {$attr} attribute has to be between {$val1},{$val2} (excluding values)"
        );
    }

    public function attrBetweenEqual(string $attr, $val1, $val2){
        $attribute  = $this->getAttrToComparison($attr);
        return $this->makeResult(
            $attribute  <= max($val1, $val2)  && $attribute  >= min($val1, $val2),
            "'s {$attr} attribute has to be between {$val1},{$val2} (including values)"
        );
    }

    private function getAttrToComparison(string $attr){
        $field = $this->model->{$attr};
        if(is_countable($field)) return count($field);    
        return $field;
    }

    private function getShortName(string $class){
        return (new \ReflectionClass(new $class ))->getShortName();
    }

    private function getPrimaryKey(string $className, $key){
        if(is_null($key)) return strtolower($className.'_id');
        else return $key;
    }

    private function getVal(string $attr){
        return $this->model->{$attr};
    }
    
    private function makeResult(bool $passes, $message){
        return new AssertionResult($passes, $this->getFailMessage($message) , $this->model_name);
    }

    public function getModel(){
        return $this->model;
    }

    public function getFailMessage($message){
        if(is_null($this->customFailMessage)) return "{$this->model_name}".$message;
        else return $this->customFailMessage;
    }

    public function setFailMessage($msg){
        $this->customFailMessage = $msg;
    }
    
}