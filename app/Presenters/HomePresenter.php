<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Models\Genre;
use App\Models\Language;
use Nette\Application\UI\Form;
use App\Models\Book;
use Ublaboo\DataGrid\DataGrid;

final class HomePresenter extends BaseAdminPresenter
{
	private Book $book;
	private Language $language;
	private int $id = 0;
	private Genre $genre;

	public function __construct(Book $book, Language $language, Genre $genre)
	{
		parent::__construct();
		$this->book = $book;
		$this->language = $language;
		$this->genre = $genre;
	}

	public function renderDefault(): void
	{

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
		$genres = $this->genre->getAllGenres();

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
			$form->addMultiSelect('genre', 'Žánr:', $genres)
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

	public function createComponentGrid(): DataGrid
	{
		$grid = new DataGrid();

		$grid->setDataSource($this->book->getAllBooks());

		$grid->setItemsPerPageList([20, 50, 100], true);

		$grid->addColumnText('title', 'Název')
			->setSortable()
			->setFilterText();

		$grid->addColumnText('author', 'Author', ':book_author.author_id')
			->setRenderer(function($item) {
				foreach ($item->related('book_author') as $author) {
					return $author->author->name;
				}
				return 'Autor nenalezen';
			})
			->setSortable(':book_author.author.name')
			->setFilterText(':book_author.author.name');

		$grid->addColumnText('isbn', 'ISBN');

		$grid->addColumnText('pages', 'Strany');

		$grid->addColumnDateTime('date', 'Vydáno')
			->setRenderer(function($item) {
				return $item->date;
			});

		$grid->addColumnText('language', 'Jazyk', ':book_language.language_id')
			->setRenderer(function($item) {
				foreach ($item->related('book_language') as $language) {
					return $language->language->name;
				}
				return 'Jazyk nenalezen';
			})
			->setSortable(':book_language.language.name')
			->setFilterText(':book_language.language.name');

		$grid->addColumnText('read', 'Přečteno')
			->setRenderer(function($item) {
				if ($item->read) {
					return 'Ano';
				}
				return 'Ne';
			})
			->setSortable();

		$grid->addColumnText('own', 'Mám')
			->setRenderer(function($item) {
				if ($item->own) {
					return 'Ano';
				}
				return 'Ne';
			})
			->setSortable();

		$grid->addColumnText('description', 'Proč ji pořídít, o čem je, co jsem si zní odnesl')
			->setFilterText();

		$grid->addColumnText('genre', 'Žánr', ':book_genre.genre_id')
			->setRenderer(function($item) {
				foreach ($item->related('book_genre') as $genre) {
					return $genre->genre->name;
				}
				return 'Žánr nenalezen';
			})
			->setSortable(':book_genre.genre.name')
			->setFilterText(':book_genre.genre.name');

		return $grid;
	}
}
