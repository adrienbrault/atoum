<?php

namespace mageekguy\atoum\Adapter;

use
	mageekguy\atoum
;

interface Aggregator
{
	public function setAdapter(atoum\Adapter $adapter);

	public function getAdapter();
}
