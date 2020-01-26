<?php

	namespace SOS
	{
		trait AccountFundManagement
		{
			public function getAmountOfCoins(): int
			{
				return $this->user->selectThis(['coins']);
			}

			public function reduceAmountOfCoins(int $reduce): void
			{
				$amount = $this->getAmountOfCoins() - $reduce;
				if ($amount < 0) $amount = 0;
				$this->user->updateThis(['coins' => $amount]);
			}

			public function addAmountOfCoins(int $add): void
			{
				$this->user->updateThis(['coins' => $this->getAmountOfCoins() + $add]);
			}

			public function hasAmountOfCoins(int $amount): bool
			{
				return $this->getAmountOfCoins() >= $amount;
			}
		}
	}

?>
