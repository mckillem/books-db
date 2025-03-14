<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Forms\FormFactory;
use Nette\Application\UI\Presenter;

abstract class BaseAdminPresenter extends Presenter
{
	/** @var FormFactory */
	protected $formFactory;

	/**
	 * @param FormFactory $formFactory
	 */
	public final function injectFormFactory(FormFactory $formFactory): void
	{
		$this->formFactory = $formFactory;
	}

	protected function startup(): void
	{
		parent::startup();
		if (!$this->getUser()->isAllowed($this->getName(), $this->getAction())) {
			$this->flashMessage('Pro tuto akci nemáš dostatečná oprávnění.');
			if (!$this->getUser()->isLoggedIn()) {
				$this->redirect('Sign:in');
			} else {
				$this->redirect('Home:');
			}
		}
	}

	public function beforeRender(): void
	{
		parent::beforeRender();
		if ($this->user->isLoggedIn()) {
			$this->template->firstname = $this->user->identity->firstname;
			$this->template->lastname = $this->user->identity->lastname;
			$this->template->email = $this->user->identity->email;
			$this->template->role = $this->user->identity->role;
		}
	}
}