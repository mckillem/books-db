<?php

declare(strict_types=1);

namespace App\Models;

use Nette\Database\Explorer;
use Nette\Database\Table\Selection;
use Nette\Security\SimpleIdentity;
use Nette\Security\User;

class Book
{
	public Explorer $db;
	private Language $language;
	private User $user;
	private Genre $genre;

	public function __construct(Explorer $db, Language $language, User $user, Genre $genre)
	{
		$this->db = $db;
		$this->language = $language;
		$this->user = $user;
		$this->genre = $genre;
	}

	public function getTable(): Selection
	{
		return $this->db->table('book');
	}

	public function getAllBooks(): Selection
	{
		return $this->getTable();
	}

	public function getAllBooksCount(): int
	{
		return $this->getTable()->count();
	}

	public function getBookById(int $id): string
	{
		$book = $this->getTable()->get($id);

		return $book->title;
	}

	public function getBook(string $searchValue): Selection
	{
		return $this->getTable()->whereOr([
			'title LIKE ?' => '%' . $searchValue . '%',
			'date' => $searchValue,
		]);
	}

	public function saveBook(\stdClass $data): void
	{
		$book = $this->getTable()->insert([
			'title' => $data->title,
			'isbn' => $data->isbn,
			'pages' => $data->pages,
			'date' => $data->date,
			'read' => $data->read,
			'own' => $data->own,
			'description' => $data->description,
//			todo: zobrazení správného data v rámci časové zony, teď je to o hodinu, test?
			'createdAt' => new \DateTime(),
//			todo: test?
			'createdBy' => $this->user->getId(),
		]);

		$author = $this->db->table('author')->insert([
			'name' => $data->author
		]);

		$this->db->table('book_author')->insert([
			'book_id' => $book->id,
			'author_id' => $author->id
		]);

		$this->db->table('book_language')->insert([
			'book_id' => $book->id,
			'language_id' => $this->language->getLanguageById($data->language)
		]);

		foreach ($data->genre as $genre) {
			$this->db->table('book_genre')->insert([
				'book_id' => $book->id,
				'genre_id' => $this->genre->getGenreById($genre)
			]);
		}
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