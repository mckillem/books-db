<?php

declare(strict_types=1);

namespace App\Models;

use Nette\Database\Explorer;
use Nette\Database\Table\Selection;

class Book
{
	public Explorer $db;

	public function __construct(Explorer $db)
	{
		$this->db = $db;
	}

	public function getTable(): Selection
	{
		return $this->db->table('book');
	}

	public function getAllBooks(): array
	{
		return $this->getTable()->fetchAll();
	}

	public function getBookById(int $id): string
	{
		$book = $this->getTable()->get($id);

		return $book->title;
	}

	public function getBook(string $searchValue): array
	{
		return $this->getTable()->whereOr([
			'title LIKE ?' => '%' . $searchValue . '%',
			'language' => $searchValue,
			'date' => $searchValue,
		])->fetchAll();
	}

	public function saveBook(\stdClass $data): void
	{
		$book = $this->getTable()->insert([
			'title' => $data->title,
			'isbn' => $data->isbn,
			'pages' => $data->pages,
			'date' => $data->date,
			'language' => $data->language,
			'read' => $data->read,
			'own' => $data->own,
			'description' => $data->description,
		]);

		$author = $this->db->table('author')->insert([
			'name' => $data->author
		]);

		$this->db->table('book_author')->insert([
			'book_id' => $book->id,
			'author_id' => $author->id
		]);
	}

//	public function deleteBook(int $id): void
//	{
//		$this->getTable()->where('id', $id)->delete();
//	}
//
//	public function updateBook(\stdClass $data): void
//	{
//		$this->getTable()->where('id', (int)$data->id)->update([
//			'title' => $data->title,
//		]);
//	}
//
//	public function findBy(array $by): Selection
//	{
//		return $this->getTable()->where('title', $by);
//	}
//
//	public function findOneBy(array $by) {
//		return $this->findBy($by)->limit(1)->fetch();
//	}
}