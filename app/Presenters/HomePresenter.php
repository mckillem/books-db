<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use App\Models\Book;

final class HomePresenter extends Nette\Application\UI\Presenter
{
	private Book $book;
	private int $id = 0;

	public function __construct(Book $book)
	{
		$this->book = $book;
	}

	public function renderDefault(): void
	{
		$this->template->books = $this->book->getAllBooks();
	}

	public function renderEdit($id): void
	{
		$this->id = (int) $id;
		$this->template->id = $id;
	}

	protected function createComponentBookForm(): Form
	{
		$form = new Form;

		$form->addHidden('id')
			->setDefaultValue($this->id);

		if ($this->id) {
			$form->addText('title')
				->setDefaultValue($this->book->getBookById($this->id))
				->setRequired();
		} else {
			$form->addText('title', 'Úkol:')
				->setRequired();
		}

		$form->addSubmit('send', 'Přidat');
		$form->onSuccess[] = $this->bookFormSucceeded(...);

		return $form;
	}

	private function bookFormSucceeded(\stdClass $data): void
	{
		if ($data->id) {
			$this->book->updateBook($data);
		} else {
			$this->book->saveBook($data);
		}

		$this->flashMessage('Úkol byl uložen', 'success');
		$this->redirect('Home:default');
	}

	public function actionDelete(int $id): void
	{
		$this->book->deleteBook($id);

		$this->flashMessage('Úkol byl smazán', 'success');
		$this->redirect('Home:default');
	}

//	public function actionUpdate(\stdClass $data, int $id): void
//	{
//		$this->book->updateBook($data, $id);
//
//		$this->flashMessage('Úkol byl upraven', 'success');
//		$this->redirect('this');
//	}
}
