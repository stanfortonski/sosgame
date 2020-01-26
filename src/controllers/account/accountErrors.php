<?php

	namespace SOS
	{
		trait AccountErrors
		{
			protected function showErrors(): string
			{
				$output = '';
				if (!empty($_SESSION['errors']))
				{
					foreach ($_SESSION['errors'] as &$error)
						$error = '<small>'.$error.'.</small>';
					$output = '<div class="error-box alert alert-danger" role="alert">'.implode('<hr>', $_SESSION['errors']).'</div>';
				}
				unset($_SESSION['errors']);
				return $output;
			}

			protected function confirmRecaptcha(): bool
			{
				$recaptcha = $_POST['g-recaptcha-response'];

				$response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=6LcgbhcUAAAAAGiGDi2Q5rSUIiVuqJw5ho7sWwyR&response='.$recaptcha.'&remoteip='.$_SERVER['REMOTE_ADDR']);
				$obj = json_decode($response);
				if (!$obj->success)
				{
					$_SESSION['errors']['recaptcha'] = 'Potwierdź swoją tożsamość';
					return false;
				}
				return true;
			}
		}
	}

?>
