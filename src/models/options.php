<?php

	namespace SOS
	{
		class Options extends DatabaseOperationsAdapter
		{
			static public function getTable(): string
			{
				return 'OPTIONS';
			}

			static public function makeDefaultOptions(): int
			{
				return static::insertThat([null, 30, 50, false, false, false, false, true, false]);
			}

			public function __construct()
			{
				parent::__construct();
			}
		}
	}

?>
