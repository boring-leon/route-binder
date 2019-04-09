**Note**: Not every method listed in this API is accessed directly inside an assertion chain. Available assertions are listed as AssertionBuilder methods

# Binder #
| returns | name |
| --- | --- |
| AssertionBuilder | ```static build(string $modelClass, $val, array $config)```  |



```php
    $config = [
        'strategy' => 'Leonc\RouteBinder\Strategy\BaseStrategy'
        'relations' => []
    ];
```

# AssertionBuilder #
| returns | name |
| --- | --- |
| AssertionResult | ```belongsTo(string $class, $val, $key = null)``` |
| AssertionResult | ```belongsToBy($targetClass, $agentClass, $val, $agentKey = null, $targetKey = null)``` |
| AssertionResult | ```hasAttr(string $attr)``` |
| AssertionResult | ```attrHasLength(string $attr, $length)``` |
| AssertionResult | ```attrEquals(string $attr, $val)``` |
| AssertionResult | ```attrEqualsStrong(string $attr, $val)``` |
| AssertionResult | ```attrTruthy(string $attr)``` |
| AssertionResult | ```attrFalsy(string $attr)``` |
| AssertionResult | ```attrGreaterThan(string $attr, $val)``` |
| AssertionResult | ```attrLessThan(string $attr, $val)``` |
| AssertionResult | ```attrGreaterEqual(string $attr, $val)``` |
| AssertionResult | ```attrLessEqual(string $attr, $val)``` |
| AssertionResult | ```attrBetween(string $attr, $val1, $val2)``` |
| AssertionResult | ```attrBetweenEqual(string $attr, $val1, $val2)``` |

# AssertionResult #
| returns | name |
| --- | --- |
bool | ```passes()```
mixed | ```getFailMessage()```
string | ```getModelName()```

# BaseStrategy #
| returns | name |
| --- | --- |
| HttpResponseException | ```fail($message, string $modelName)```  |
| Model | ```bind(Model $model)```  |
| Model | ```getModel(Model $rawInstance, string $key, array $relations)```  |
| bool | ```exists(Model $model)```  |


# AssertionInvoker #
| returns | name |
| --- | --- |
| $this | ```failMessage($message = null)```  |
| $this | ```persistFailMessage($message```)  |
| $this | ```strategy($class = null)```  |
| $this | ```persistStrategy(string $class)```  |
| Model | ```bind()``` |


