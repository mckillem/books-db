<?php
declare(strict_types=1);

namespace App\Models;

use Nette\Security\Passwords;
use Nette\Database\Table\Selection;
use Nette\SmartObject;
use Nette\Security\Authenticator;
use Nette\Security\IIdentity;
use Nette\Security\SimpleIdentity;
use Nette\Security\AuthenticationException;
use Nette\Database\Explorer;

final class UserManager implements Authenticator
{
	use SmartObject;

	private const
		TABLE_NAME = 'user',
		COLUMN_ID = 'id',
		COLUMN_PASSWORD_HASH = 'password',
		COLUMN_EMAIL = 'email',
		COLUMN_FIRSTNAME = 'firstname',
		COLUMN_LASTNAME = 'lastname',
		COLUMN_ROLE = 'role';

	private Explorer $database;

	private Passwords $passwords;

	public function __construct(Explorer $database, Passwords $passwords)
	{
		$this->database = $database;
		$this->passwords = $passwords;
	}

	/**
	 * @throws AuthenticationException
	 */
	public function authenticate(string $username, string $password): IIdentity
	{
		$row = $this->database->table(self::TABLE_NAME)
			->where(self::COLUMN_EMAIL, $username)
			->fetch();

		if (!$row) {
			throw new AuthenticationException('Zadali jste nesprávný email.', Authenticator::IdentityNotFound);
		} elseif (!$this->passwords->verify($password, $row[self::COLUMN_PASSWORD_HASH])) {
			throw new AuthenticationException('Vaše heslo není správné.', Authenticator::InvalidCredential);
		} elseif ($this->passwords->needsRehash($row[self::COLUMN_PASSWORD_HASH])) {
			$row->update([
				self::COLUMN_PASSWORD_HASH => $this->passwords->hash($password),
			]);
		}

		$arr = $row->toArray();
		unset($arr[self::COLUMN_PASSWORD_HASH]);

		return new SimpleIdentity($row[self::COLUMN_ID], $row[self::COLUMN_ROLE], $arr);
	}

	public function getUsers(): Selection {
		return $this->database->table(self::TABLE_NAME);
	}
}