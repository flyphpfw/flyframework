<?php

use Mockery as m;

class ValidationDatabasePresenceVerifierTest extends PHPUnit_Framework_TestCase {

	public function tearDown()
	{
		m::close();
	}


	public function testBasicCount()
	{
		$verifier = new Fly\Validation\DatabasePresenceVerifier($db = m::mock('Fly\Database\ConnectionResolverInterface'));
		$verifier->setConnection('connection');
		$db->shouldReceive('connection')->once()->with('connection')->andReturn($conn = m::mock('StdClass'));
		$conn->shouldReceive('table')->once()->with('table')->andReturn($builder = m::mock('StdClass'));
		$builder->shouldReceive('where')->with('column', '=', 'value')->andReturn($builder);
		$extra = array('foo' => 'NULL', 'bar' => 'NOT_NULL', 'baz' => 'allan');
		$builder->shouldReceive('whereNull')->with('foo');
		$builder->shouldReceive('whereNotNull')->with('bar');
		$builder->shouldReceive('where')->with('baz', 'allan');
		$builder->shouldReceive('count')->once()->andReturn(100);

		$this->assertEquals(100, $verifier->getCount('table', 'column', 'value', null, null, $extra));
	}

}