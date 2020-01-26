<?php

	namespace MailSender
	{
		class Mail
		{
			private $headers;
			private $sheet;

			public function makeDefaultSheet()
			{
				return new \STV\Sheets\Mail;
			}

			public function __construct(string $yourName, string $from, \STV\Sheet $sheet = null)
			{
				$this->headers  = "Content-type: text/html; charset=utf-8'\r\n";
				$this->headers .= "From: {$yourName} <{$from}>\r\n";
				$this->headers .= "Reply-To: {$from}";

				$this->sheet = empty($sheet) ? $this->makeDefaultSheet() : $sheet;
			}

			public function getHeaders(): string
			{
				return $this->headers;
			}

			public function getContent(string $title, string $message): string
			{
				ob_start();
				$window = new \STV\Window($this->sheet);
				$window->setTitle($title);
				$window->content()->append($message);
				$window->display();
				$content = ob_get_contents();
				ob_end_clean();
				return $content;
			}
		}

		class Sender
		{
			private $mailType;

			public function __construct(Mail $mailType)
			{
				$this->mailType = $mailType;
			}

			public function changeMailType(Mail $mailType): void
			{
				$this->mailType = $mailType;
			}

			public function send(string $to, string $title, string $message): bool
			{
				$to = filter_var($to, FILTER_SANITIZE_EMAIL);
				if (!filter_var($to, FILTER_VALIDATE_EMAIL))
					return false;
				return mail($to, $title, $this->mailType->getContent($title, $message), $this->mailType->getHeaders());
			}
		}
	}

?>
