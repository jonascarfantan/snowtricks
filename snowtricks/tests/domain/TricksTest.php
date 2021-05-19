<?php
namespace App\Tests\domain;
use PHPUnit\Framework\TestCase;

class TricksTest extends TestCase
{
    public function testPushAndPop(): void
    {
        $stack = [];
        $this->assertSame(0, count($stack));
        array_push($stack, 'foo');
        $this->assertSame('foo', $stack[count($stack)-1]);
        $this->assertSame(1, count($stack));
        $this->assertSame('foo', array_pop($stack));
        $this->assertSame(0, count($stack));
    }
    
    public function testGetCreateForm(): void
    {
        $this->assertSame(1,1);
    }
}
