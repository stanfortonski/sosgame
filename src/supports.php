<?php

	function array_htmlentities(array &$arr): array
	{
		array_walk_recursive($arr, function(&$value){
			$value = htmlentities($value);
		});
		return $arr;
	}

	function randomString(int $length): string
	{
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		return substr(str_shuffle($chars), 0, $length);
	}

?>
