<?php

namespace mageekguy\atoum\mock;

use
	mageekguy\atoum\mock
;

interface Aggregator
{
	public function getMockController();
	public function setMockController(mock\controller $mockController);
	public function resetMockController();
}
