<?php

	namespace SOS
	{
		trait TimeManagerInterface
		{
      protected function getTimeAfterFormatAndAddingSeconds(int $seconds): string
      {
        $date = new \DateTime;
        $date->add(new \DateInterval('PT'.$seconds.'S'));
        return $date->format('Y-m-d H:i:s');
      }

      protected function diffInSeconds($timeFromFormat): int
      {
        $date = new \DateTime($timeFromFormat);
        return $date->getTimestamp() - time();
      }
		}
	}

?>
