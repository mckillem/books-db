<?php

declare(strict_types=1);

namespace App\Forms;

use Nette\Application\UI\Form;
use Nette\Security\User;
use Nette\Security\AuthenticationException;

final class SignInFormFactory
{
	/** @var FormFactory */
	private FormFactory $factory;

	/** @var User */
	private User $user;

	public function __construct(FormFactory $factory, User $user)
	{
		$this->factory = $factory;
		$this->user = $user;
	}

	public function create(callable $onSuccess): Form
	{
		$form = $this->factory->create();
		$form->addEmail('email', 'Email:')
			->setRequired('Zadejte email.');
		$form->addPassword('password', 'Password:')
			->setRequired('Zadejte heslo.');
		$form->addSubmit('send', 'Přihlásit');
		$form->onSuccess[] = function (Form $form, \stdClass $values) use ($onSuccess): void
		{
			try {
				$this->user->login($values->email, $values->password);
			} catch (AuthenticationException $e) {
				$form->addError('Email či heslo je špatně zadáno.');

				return;
			}
			$onSuccess();
		};

		return $form;
	}
}