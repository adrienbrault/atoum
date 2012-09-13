<?php

namespace mageekguy\atoum\adapter;

interface Definition
{
	public function __call($functionName, $arguments);

	public function invoke($functionName, array $arguments = array());
}