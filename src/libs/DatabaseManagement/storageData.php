<?php

	namespace DBManagement
	{
		class StorageData
		{
			private $data = [];

			public function __construct(array $arr)
			{
				$this->data = $arr;
			}

			public function get(string $key)
			{
				if (isset($this->data[$key]))
					return $this->data[$key];
				return null;
			}

			public function getAll(): array
			{
				return $this->data;
			}

			public function toJSON(): string
			{
				return json_encode($this->data);
			}

			static public function fromJSON(string $json): self
			{
				return new self(json_decode($json, true));
			}
		}
	}

?>
