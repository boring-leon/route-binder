<?php

namespace Leonc\RouteBinder\Tests;

use Tests\TestCase;
use Leonc\RouteBinder\Assertion\AssertionBuilder;
use App\User;

class AssertionBuilderTest extends TestCase
{
    public function testHasAttr(){
        $this->setBuilder(['name' => 'Frank']);
        $this->assertTrue($this->builder->hasAttr('name')->passes());
        $this->assertFalse($this->builder->hasAttr('age')->passes());
    }

    public function testAttrEquals(){
        $this->setBuilder(['id' => '4']);
        $this->assertTrue($this->builder->attrEquals('id', '4')->passes() );
        $this->assertTrue($this->builder->attrEquals('id', 4)->passes() );
        $this->assertFalse($this->builder->attrEquals('id', 5)->passes() );
    }

    public function testAttrEqualsStrong(){
        $this->setBuilder(['id' => 4 ]);
        $this->assertTrue($this->builder->attrEquals('id', 4)->passes() );
        $this->assertFalse($this->builder->attrEqualsStrong('id', '4')->passes() );
    }

    public function testBooleanShorthands(){
        $this->setBuilder(['is_admin' => true, 'is_rich' => false]);
        $this->assertEquals( 
            $this->builder->attrTruthy('is_admin'),
            $this->builder->attrEquals('is_admin', true)
        );
        $this->assertEquals(
            $this->builder->attrFalsy('is_rich'),
            $this->builder->attrEquals('is_rich', false)
        );
    }

    public function testHasLengthOnNumeric(){
        $this->setBuilder(['friends' => 2]);
        $this->assertTrue($this->builder->attrHasLength('friends', 2)->passes() );
    }

    public function testHasLengthOnCountable(){
        $this->setBuilder(['friends' => ['John', 'Mark' ] ]);
        $this->assertTrue($this->builder->attrHasLength('friends', 2)->passes() );
    }

    public function testAttrBetween(){
        $this->setBuilder(['salary' => 500]);
        $this->assertTrue($this->builder->attrBetween('salary', 200, 600)->passes());
        $this->assertFalse($this->builder->attrBetween('salary', 100, 450)->passes());
    }

    public function testAttrBetweenEqual(){
        $this->setBuilder(['salary' => 500]);
        $this->assertTrue($this->builder->attrBetweenEqual('salary', 200, 500)->passes());
    }

    private function setBuilder(array $modelData){
        $user = new User;
        $user->fill($modelData);
        $this->builder = new AssertionBuilder($user);
    }
}
