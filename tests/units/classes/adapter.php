<?php

namespace mageekguy\atoum\tests\units;

use
	mageekguy\atoum
;

require_once __DIR__ . '/../runner.php';

class adapter extends atoum\test
{
	public function test__construct()
	{
		$this
			->dump('foo')
			->if($asserter = new \mock\mageekguy\atoum\asserter($generator = new atoum\asserter\Generator()))
			->then
				->object($asserter->getGenerator())->isIdenticalTo($generator)
		;
	}

	public function test__call()
	{
		$this
			->if($adapter = new atoum\adapter())
			->then
				->string($adapter->md5($hash = uniqid()))->isEqualTo(md5($hash))
		;
	}
}
