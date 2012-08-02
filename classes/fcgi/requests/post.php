<?php

namespace mageekguy\atoum\fcgi\requests;

use
	mageekguy\atoum\fcgi
;

class post extends fcgi\request implements \arrayAccess
{
	public function offsetSet($name, $value)
	{
		$variables = $this->getVariablesFromStdin();

		$variables[$name] = $value;

		return $this->buildStdin($variables);
	}

	public function offsetGet($name)
	{
		$variables = $this->getVariablesFromStdin();

		return (isset($variables[$name]) === false ? null : $variables[$name]);
	}

	public function offsetUnset($name)
	{
		$variables = $this->getVariablesFromStdin();

		if (isset($variables[$name]) === true)
		{
			unset($variables[$name]);
		}

		return $this->buildStdin($variables);
	}

	public function offsetExists($name)
	{
		$variables = $this->getVariablesFromStdin();

		return isset($variables[$name]);
	}

	private function buildStdin(array $variables)
	{
		$this->setStdin(http_build_query($variables, ''))->content_length = sizeof($this->stdin);

		return $this;
	}

	private function getVariablesFromStdin()
	{
		parse_str($this->getStdin(), $variables);

		return $variables;
	}
}
