<?php

	declare(strict_types=1);

	require_once dirname(__FILE__).'/../run.php';

	use PHPUnit\Framework\TestCase;

	class UserTest extends TestCase
	{
		const LOGIN = 'login';
		const MAIL = 'mail';
		const PASSWORD = 'password';
		const NEW_PASSWORD = 'newpassword';

		static private $user;

		private function clearDataBeforeStartTests(): void
		{
			$pdo = (new DBManagement\DatabaseConnection)->getConnect();
			$operation = $pdo->prepare('SELECT id FROM USERS WHERE login = ? AND mail = ?');
			$operation->execute([self::LOGIN, self::MAIL]);

			$id = $result = $operation->fetch();
			if (!empty($id)){
				$operation = $pdo->prepare('DELETE FROM USERS WHERE id = ?');
				$operation->execute([$id]);
				$operation->closeCursor();

				$operation = $pdo->prepare('DELETE FROM USERS_TO_ACTIVATION WHERE id = ?');
				$operation->execute([$id]);
				$operation->closeCursor();

				$operation = $pdo->prepare('DELETE FROM USERS_CONFIRM_EMAILS WHERE id = ?');
				$operation->execute([$id]);
				$operation->closeCursor();

				$operation = $pdo->prepare('DELETE FROM USERS_TO_DELETE WHERE id = ?');
				$operation->execute([$id]);
				$operation->closeCursor();
			}
		}

		public function testRegisterNewAccount(): void
		{
			$this->clearDataBeforeStartTests();
			self::$user = new SOS\User;

			$this->assertTrue(self::$user->isLoginAvailable(self::LOGIN));
			$this->assertTrue(self::$user->isMailAvailable(self::MAIL));
			$id = self::$user->register(self::LOGIN, self::MAIL, self::PASSWORD);
			$this->assertNotEmpty($id);

			$data = self::$user->selectThis();
			$this->assertEquals(self::LOGIN, $data['login']);
			$this->assertEquals(self::MAIL, $data['mail']);
			$this->assertTrue(password_verify(self::PASSWORD, $data['password']));

			$this->assertFalse(self::$user->isLoginAvailable(self::LOGIN));
			$this->assertFalse(self::$user->isMailAvailable(self::MAIL));
		}

		/**
		 * @depends testRegisterNewAccount
		 */
		public function testloginToAccount(): void
		{
			$this->assertTrue(self::$user->login(self::LOGIN, self::PASSWORD));
			$this->assertTrue(self::$user->login(self::MAIL, self::PASSWORD));
		}

		/**
		 * @depends testloginToAccount
		 */
		public function testChangingPassword(): void
		{
			self::$user->changePassword(self::NEW_PASSWORD);
			$actualPassword = self::$user->selectThis(['password']);
			$this->assertTrue(password_verify(self::NEW_PASSWORD, $actualPassword));
		}

		/**
		 * @depends testloginToAccount
		 */
		public function testUsersActivation(): void
		{
			$link = self::$user->getActivationLink();
			$this->assertNotEmpty($link);
			$this->assertFalse(self::$user->isVerified());
			$this->assertTrue(self::$user->activation($link));
			$this->assertTrue(self::$user->isVerified());
			$this->assertEmpty(self::$user->getActivationLink());
		}

		/**
		 * @depends testloginToAccount
		 */
		public function testUserConfirmMail(): void
		{
			$mail = 'test@test.pl';
			$this->assertEmpty(self::$user->getConfirmNewMailLink());
			self::$user->prepareConfirmNewMail($mail);

			$link = self::$user->getConfirmNewMailLink();
			$this->assertNotEmpty($link);
			$this->assertTrue(self::$user->confirmNewMail($link));

			$actualMail = self::$user->selectThis(['mail']);
			$this->assertEquals($actualMail, $mail);
			$this->assertEmpty(self::$user->getConfirmNewMailLink());
		}

		/**
		 * @depends testRegisterNewAccount
		 */
		public function testRemovingUsersData(): void
		{;
			$this->assertFalse(self::$user->isInDeletePool());
			$link = self::$user->addToDeletePool();
			$this->assertNotEmpty($link);
			$this->assertTrue(self::$user->isInDeletePool());

			self::$user->removeFromDeletePool();
			$this->assertFalse(self::$user->isInDeletePool());
			$this->assertNotEmpty($link = self::$user->addToDeletePool());
			$this->assertTrue(self::$user->isInDeletePool());

			self::$user->useTable('USERS_TO_DELETE');
			$this->assertEmpty(self::$user->selectThis(['end_date']));
			self::$user->useTable('USERS');
			self::$user->confirmDelete($link);
			self::$user->useTable('USERS_TO_DELETE');
			$this->assertNotEmpty(self::$user->selectThis(['end_date']));

			self::$user->updateThis(['end_date' => '2018-01-01 12:00:00']);
			self::$user->useTable('USERS');

			SOS\User::deleteUsersInPool();

			$this->assertEquals(self::$user->selectThis(), []);
			$this->assertEquals(self::$user->getOptionsManipulator()->getId(), 0);
			$this->assertEquals(self::$user->getPlayerManipulator()->selectThis(), []);
		}
	}

?>
