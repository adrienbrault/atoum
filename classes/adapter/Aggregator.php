<?php

namespace mageekguy\atoum\adapter;

use
	mageekguy\atoum
;

interface Aggregator
{
	public function setAdapter(atoum\adapter $adapter);

	public function getAdapter();
}
