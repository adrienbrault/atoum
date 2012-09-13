<?php

namespace mageekguy\atoum;

use
	mageekguy\atoum
;

class cli
{
	private static $isTerminal = null;

	public function __construct(atoum\Adapter $adapter = null)
	{
		if ($adapter === null)
		{
			$adapter = new atoum\Adapter();
		}

		if (self::$isTerminal === null)
		{
			self::$isTerminal = $adapter->defined('PHP_WINDOWS_VERSION_BUILD') ? (Boolean) $adapter->getenv('ANSICON') : ($adapter->defined('STDOUT') === true && $adapter->function_exists('posix_isatty') === true && $adapter->posix_isatty($adapter->constant('STDOUT')) === true);
		}
	}

	public function setAdapter(atoum\Adapter $adapter)
	{
		$this->adapter = $adapter;

		return $this;
	}

	public function getAdapter()
	{
		return $this->adapter;
	}

	public function isTerminal()
	{
		return self::$isTerminal;
	}

	public static function forceTerminal()
	{
		self::$isTerminal = true;
	}
}
