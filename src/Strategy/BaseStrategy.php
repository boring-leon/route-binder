<?php

namespace Leonc\RouteBinder\Strategy;

use Illuminate\Database\Eloquent\Model;

class BaseStrategy
{
    public function fail($message, string $modelName){
        return response()->json(['problem' => $message])->throwResponse();
    }

    public function bind($model){
        return $model;
    }

    public function getModel(Model $model, $param, array $relations){
        $instance = $model->find($param);
        if(is_null($instance)) return null;

        if(count($relations) == 0){
            return $instance;
        }
        else{
            return $instance->with($relations)->first();
        }
    }

    public function exists($model){
        return is_null($model) == false;
    }

}