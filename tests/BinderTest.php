<?php

namespace Leonc\RouteBinder\Tests;

use Tests\TestCase;

use Leonc\RouteBinder\Binder;
use Leonc\RouteBinder\Assertion\AssertionInvoker;

class BinderTest extends TestCase
{
    public function testBuildReturnsAssertionInvokerInstance(){

        $invoker = Binder::build(\App\User::class, 1);
        $this->assertInstanceOf(AssertionInvoker::class, $invoker);
    }
}
