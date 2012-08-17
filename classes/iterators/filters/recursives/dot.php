<?php

namespace mageekguy\atoum\iterators\filters\recursives;

use
	mageekguy\atoum
;

class dot extends \recursiveFilterIterator
{
	public function __construct($mixed, $dependencies = null)
	{
		if ($mixed instanceof \recursiveIterator)
		{
			parent::__construct($mixed);
		}
		else
		{
			if ($dependencies === null)
			{
				$dependencies = new atoum\dependencies();
			}

			$dependencies['iterator'] = $dependencies['iterator'] ?: new \recursiveDirectoryIterator((string) $mixed);

			$dependencies['iterator']['directory'] = (string) $mixed;

			parent::__construct($dependencies['iterator']());
		}
	}

	public function accept()
	{
		return (substr(basename((string) $this->getInnerIterator()->current()), 0, 1) != '.');
	}
}
