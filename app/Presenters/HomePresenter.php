<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Models\Author;
use App\Models\Language;
use Nette\Application\UI\Form;
use App\Models\Book;
use Nette\Database\Table\Selection;

final class HomePresenter extends BaseAdminPresenter
{
	private Book $book;
	private Author $author;
	private Language $language;
	private int $id = 0;
	private Selection $books;

	public function __construct(Book $book, Author $author, Language $language)
	{
		parent::__construct();
		$this->book = $book;
		$this->author = $author;
		$this->language = $language;
		$this->books = $this->book->getAllBooks();
	}

	public function renderDefault(int $page = 1): void
	{
		$lastPage = 0;
		$this->template->books = $this->books->page($page, 10, $lastPage);
		$this->template->page = $page;
		$this->template->lastPage = $lastPage;
//		$this->template->authors = $this->author->getAllAuthors();
	}

	public function renderEdit($id): void
	{
		$this->id = (int) $id;
		$this->template->id = $id;
	}

	protected function createComponentBookForm(): Form
	{
//		$authors = $this->author->getAllAuthors();
		$languages = $this->language->getAllLanguages();

		$form = new Form;

		$form->addHidden('id')
			->setDefaultValue($this->id);

		if ($this->id) {
			$form->addText('title')
				->setDefaultValue($this->book->getBookById($this->id))
				->setRequired();
		} else {
			$form->addText('title', 'Název:')
				->setRequired();
//			$form->addMultiSelect('author', 'Autor:', $authors)
			$form->addText('author', 'Autor:')
				->setRequired();
			$form->addText('isbn', 'ISBN:')
				->setRequired();
			$form->addInteger('pages', 'Počet stran:')
				->setRequired();
			$form->addDate('date', 'Datum:')
				->setFormat('Y')
				->setRequired();
			$form->addSelect('language', 'Jazyk:', $languages)
				->setPrompt('Vyber jazyk')
				->setRequired();
			$form->addCheckbox('read', 'Přečteno:');
			$form->addCheckbox('own', 'Mám:');
			$form->addTextArea('description', 'Proč ji pořídít, o čem je, co jsem si zní odnesl:')
				->setRequired();
		}

		$form->addSubmit('send', 'Přidat');
		$form->onSuccess[] = $this->bookFormSucceeded(...);

		return $form;
	}

	private function bookFormSucceeded(\stdClass $data): void
	{
		if ($data->id) {
//			$this->book->updateBook($data);
		} else {
			$this->book->saveBook($data);
		}

		$this->flashMessage('Kniha byla uložena', 'success');
		$this->redirect('Home:default');
	}

//	public function actionDelete(int $id): void
//	{
//		$this->book->deleteBook($id);
//
//		$this->flashMessage('Kniha byla smazána', 'success');
//		$this->redirect('Home:default');
//	}

//	public function actionUpdate(\stdClass $data, int $id): void
//	{
//		$this->book->updateBook($data, $id);
//
//		$this->flashMessage('Úkol byl upraven', 'success');
//		$this->redirect('this');
//	}

//	public function handleAuthor($author_id)
//	{
////		if(Validators::isNumericInt($author_id) !== true)
////		{
////			$this->flashMessage('Autor musí být zadán podle jeho ID','alert alert-danger');
////			$this->redirect('default');
////		}
////
////		if(($this->context->author->find($author_id)) == NULL) {
////			$this->flashMessage("Autor nebyl nalezen.", "alert alert-danger");
////			$this->redirect("default");
////		}
//
//		$arr = array();
//
//		foreach($this->context->booksAuthors->findBy(array('author_id'=>$author_id)) as $book)
//		{
//			$arr[] = $book->books_id;
//		}
//		$this->sql = $this->context->books->findBy(array('id'=>$arr));
//	}
//	public function handleBook($book_id)
//	{
////		if(Validators::isNumericInt($author_id) !== true)
////		{
////			$this->flashMessage('Autor musí být zadán podle jeho ID','alert alert-danger');
////			$this->redirect('default');
////		}
////
////		if(($this->context->author->find($author_id)) == NULL) {
////			$this->flashMessage("Autor nebyl nalezen.", "alert alert-danger");
////			$this->redirect("default");
////		}
//
//		$arr = array();
//
//		foreach($this->context->booksAuthors->findBy(array('book_id'=>$book_id)) as $author)
//		{
//			$arr[] = $author->authors_id;
//		}
//		$this->sql = $this->context->authors->findBy(array('id'=>$arr));
//	}

	public function createComponentSearchForm(): Form
	{
		$form = new Form();
		$form->addText('text');
		$form->addSubmit('submit', 'Vyhledat knihu či autora');

		$form->onSuccess[] = $this->searchFormSucceeded(...);

		return $form;
	}

	private function searchFormSucceeded(\stdClass $data): void
	{
		$this->books = $this->book->getBook($data->text);

		$this->template->authors = $this->author->getAuthor($data->text);
	}
}
