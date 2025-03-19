<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette\Application\UI\Form;
use App\Forms\SignInFormFactory;

final class SignPresenter extends BaseAdminPresenter
{
	/** @persistent */
	public String $backlink = '';

	/** @var SignInFormFactory */
	private SignInFormFactory $signInFactory;


	public function __construct(SignInFormFactory $signInFactory)
	{
		parent::__construct();
		$this->signInFactory = $signInFactory;
	}

	protected function createComponentSignInForm(): Form
	{
		return $this->signInFactory->create(function (): void
		{
			$this->restoreRequest($this->backlink);
			$this->redirect('Home:');
		});
	}

//	public function actionOut(): void
//	{
//		$this->getUser()->logout();
//	}
}