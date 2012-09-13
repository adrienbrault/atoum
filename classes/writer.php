<?php

namespace mageekguy\atoum;

abstract class writer implements adapter\Aggregator
{
	protected $adapter = null;

	public function __construct(Adapter $adapter = null)
	{
		$this->setAdapter($adapter ?: new Adapter());
	}

	public function setAdapter(Adapter $adapter)
	{
		$this->adapter = $adapter;

		return $this;
	}

	public function getAdapter()
	{
		return $this->adapter;
	}

	public abstract function write($string);
	public abstract function clear();
}
