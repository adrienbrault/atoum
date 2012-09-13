<?php

namespace mageekguy\atoum\tests\units\factory;

use
	mageekguy\atoum,
	mageekguy\atoum\exceptions
;

require __DIR__ . '/../../runner.php';

class Exception extends atoum\test
{
	public function testClass()
	{
		$this->assert
			->testedClass->isSubclassOf('mageekguy\atoum\exceptions\runtime')
		;
	}
}
