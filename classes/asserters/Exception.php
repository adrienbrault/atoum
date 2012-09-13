<?php

namespace mageekguy\atoum\asserters;

use
	mageekguy\atoum\asserters,
	mageekguy\atoum\exceptions,
	mageekguy\atoum\tools\diffs
;

class Exception extends asserters\object
{
	public function setWith($value, $label = null, $check = true)
	{
		$exception = $value;

		if ($exception instanceof \closure)
		{
			$exception = null;

			try
			{
				$value();
			}
			catch (\Exception $exception) {}
		}

		parent::setWith($exception, $label, false);

		if ($check === true)
		{
			if (self::isException($exception) === false)
			{
				$this->fail(sprintf($this->getLocale()->_('%s is not an exception'), $this));
			}
			else
			{
				$this->pass();
			}
		}

		return $this;
	}

	public function isInstanceOf($value, $failMessage = null)
	{
		try
		{
			self::check($value, __FUNCTION__);
		}
		catch (\logicException $exception)
		{
			if (self::classExists($value) === false || (ucfirst(strtolower(ltrim($value, '\\'))) !== 'Exception' && is_subclass_of($value, 'Exception') === false))
			{
				throw new exceptions\logic\invalidArgument('Argument of ' . __METHOD__ . '() must be a \Exception instance or an exception class name');
			}
		}

		return parent::isInstanceOf($value, $failMessage);
	}

	public function hasDefaultCode($failMessage = null)
	{
		if ($this->valueIsSet()->value->getCode() === 0)
		{
			return $this->pass();
		}
		else
		{
			$this->fail($failMessage !== null ? $failMessage : sprintf($this->getLocale()->_('code is %s instead of 0'), $this->value->getCode()));
		}
	}

	public function hasCode($code, $failMessage = null)
	{
		if ($this->valueIsSet()->value->getCode() === $code)
		{
			return $this->pass();
		}
		else
		{
			$this->fail($failMessage !== null ? $failMessage : sprintf($this->getLocale()->_('code is %s instead of %s'), $this->value->getCode(), $code));
		}
	}

	public function hasMessage($message, $failMessage = null)
	{
		if ($this->valueIsSet()->value->getMessage() == (string) $message)
		{
			return $this->pass();
		}
		else
		{
			$this->fail($failMessage !== null ? $failMessage : sprintf($this->getLocale()->_('message \'%s\' is not identical to \'%s\''), $this->value->getMessage(), $message));
		}
	}

	public function hasNestedException(\Exception $exception = null, $failMessage = null)
	{
		if ($exception === null)
		{
			if ($this->valueIsSet()->value->getPrevious() !== null)
			{
				return $this->pass();
			}
			else
			{
				$this->fail($failMessage !== null ? $failMessage : $this->getLocale()->_('exception does not contain any nested exception'));
			}
		}
		else
		{
			if ($this->valueIsSet()->value->getPrevious() == $exception)
			{
				return $this->pass();
			}
			else
			{
				$this->fail($failMessage !== null ? $failMessage : $this->getLocale()->_('exception does not contain this nested exception'));
			}
		}
	}

	protected function valueIsSet($message = 'Exception is undefined')
	{
		return parent::valueIsSet($message);
	}

	protected static function check($value, $method)
	{
		if (self::isException($value) === false)
		{
			throw new exceptions\logic\invalidArgument('Argument of ' . __CLASS__ . '::' . $method . '() must be an exception instance');
		}
	}

	protected static function isException($value)
	{
		return (parent::isObject($value) === true && $value instanceof \Exception === true);
	}
}
