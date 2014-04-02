<?php
function __autoload($class_name)
{
	require("all_class_map.php");
	$class_file = $all_class_map[$class_name];
	if($class_file)
		require_once ($class_file);
	elseif($class_name)
	{
		$trace = debug_backtrace();
		if (count($trace) < 1 || ($trace[1]['function'] != 'class_exists' && $trace[1]['function'] != 'is_a'))
		{
			trigger_error("Class $class_name is not defined.", E_USER_ERROR);
		}
	}
}
?>
