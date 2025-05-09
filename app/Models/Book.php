<?php

declare(strict_types=1);

namespace App\Models;

use Nette\Database\Explorer;
use Nette\Database\Table\Selection;
use Nette\Security\User;
use Nette\Utils\FileSystem;

class Book
{
	public function __construct(
		public Explorer $db,
		private Language $language,
		private User $user,
		private Genre $genre,
		private Author $author
	)
	{
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

		foreach ($data->author as $author) {
			$this->db->table('book_author')->insert([
				'book_id' => $book->id,
				'author_id' => $this->author->getAuthorById($author)
			]);
		}

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

		$fileName = $data->file->name;
		$file = $this->db->table('file')->insert([
			'file_name' => $fileName,
			'type' => substr($fileName, strrpos($fileName, '.') + 1),
//			'createdAt' => new \DateTime(),
		]);

		FileSystem::copy((string)$data->file, 'images/' . $fileName);

		$this->db->table('book_file')->insert([
			'book_id' => $book->id,
			'file_id' => $file->id,
		]);

		$imageName = $data->image->name;
		$image = $this->db->table('file')->insert([
			'file_name' => $imageName,
			'type' => substr($imageName, strrpos($imageName, '.') + 1),
//			'createdAt' => new \DateTime(),
		]);

		FileSystem::copy((string)$data->image, 'images/' . $imageName);

		$this->db->table('book_file')->insert([
			'book_id' => $book->id,
			'file_id' => $image->id,
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