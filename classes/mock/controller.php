<?php

namespace mageekguy\atoum\mock;

use
	mageekguy\atoum,
	mageekguy\atoum\mock,
	mageekguy\atoum\test,
	mageekguy\atoum\exceptions
;

class controller extends test\adapter
{
	protected $mockClass = null;

	protected static $controlNextNewMock = null;

	private $disableMethodChecking = false;

	public function __construct(atoum\dependencies $dependencies = null)
	{
		parent::__construct($dependencies);

		$this
			->setDependencies($dependencies ?: new atoum\dependencies())
			->controlNextNewMock()
		;
	}

	public function __set($method, $mixed)
	{
		$this->checkMethod($method);

		parent::__set($method, $mixed);
	}

	public function __get($method)
	{
		$this->checkMethod($method);

		return parent::__get($method);
	}

	public function __isset($method)
	{
		$this->checkMethod($method);

		return parent::__isset($method);
	}

	public function __unset($method)
	{
		$this->checkMethod($method);

		parent::__unset($method);

		$this->invokers[strtolower($method)] = null;

		return $this;
	}

	public function setDependencies(atoum\dependencies $dependencies)
	{
		$dependencies['reflection\class'] = $dependencies['reflection\class'] ?: function($dependencies) { return new \reflectionClass($dependencies['class']()); };

		return parent::setDependencies($dependencies);
	}

	public function disableMethodChecking()
	{
		$this->disableMethodChecking = true;

		return $this;
	}

	public function getMockClass()
	{
		return $this->mockClass;
	}

	public function getCalls($method = null, array $arguments = null, $identical = false)
	{
		if ($method !== null)
		{
			$this->checkMethod($method);
		}

		return parent::getCalls($method, $arguments, $identical);
	}

	public function control(mock\aggregator $mock)
	{
		$mockClass = get_class($mock);

		if ($this->mockClass !== $mockClass)
		{
			$this->mockClass = $mockClass;

			$class = $this->dependencies['reflection\class'](array('class' => $this->mockClass));

			$methods = array_filter($class->getMethods(\reflectionMethod::IS_PUBLIC), function ($value) {
					try
					{
						return ($value->getPrototype()->getName() != __NAMESPACE__ . '\aggregator');
					}
					catch (\exception $exception)
					{
						return true;
					}
				}
			);

			array_walk($methods, function(& $value) { $value = strtolower($value->getName()); });

			foreach (array_keys($this->invokers) as $method)
			{
				if (in_array($method, $methods) === false)
				{
					throw new exceptions\logic('Method \'' . $this->mockClass . '::' . $method . '()\' does not exist');
				}
			}

			foreach ($methods as $method)
			{
				if (isset($this->invokers[$method]) === false)
				{
					$this->invokers[$method] = null;
				}
			}

			$mock->setMockController($this);
		}

		if (self::$controlNextNewMock === $this)
		{
			self::$controlNextNewMock = null;
		}

		return $this;
	}

	public function controlNextNewMock()
	{
		self::$controlNextNewMock = $this;

		return $this;
	}

	public function notControlNextNewMock()
	{
		if (self::$controlNextNewMock === $this)
		{
			self::$controlNextNewMock = null;
		}

		return $this;
	}

	public function reset()
	{
		$this->mockClass = null;

		return parent::reset();
	}

	public function invoke($method, array $arguments = array())
	{
		$this->checkMethod($method);

		if (isset($this->{$method}) === false)
		{
			throw new exceptions\logic('Method ' . $method . '() is not under control');
		}

		return parent::invoke($method, $arguments);
	}

	public static function get()
	{
		$instance = self::$controlNextNewMock;

		if ($instance !== null)
		{
			self::$controlNextNewMock = null;
		}

		return $instance;
	}

	public function injectInNextMockInstance()
	{
		#DEPRECATED
		die(__METHOD__ . ' is deprecated, please use ' . __CLASS__ . '::controlNextNewMock() instead');
	}

	protected function checkMethod($method)
	{
		if ($this->mockClass !== null && $this->disableMethodChecking === false)
		{
			if (array_key_exists(strtolower($method), $this->invokers) === false)
			{
				throw new exceptions\logic('Method \'' . $this->mockClass . '::' . $method . '()\' does not exist');
			}
		}

		return $this;
	}
}
