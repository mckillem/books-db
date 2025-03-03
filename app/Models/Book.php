<?php

declare(strict_types=1);

namespace App\Models;

use Nette\Database\Explorer;

class Book
{
	public Explorer $db;

	public function __construct(Explorer $db)
	{
		$this->db = $db;
	}

	public function getAllBooks(): array
	{
		return $this->db->table('book')->fetchAll();
	}

	public function getBookById(int $id): string
	{
		$book = $this->db->table('book')->get($id);

		return $book->title;
	}

	public function saveBook(\stdClass $data): void
	{
		$book = $this->db->table('book')->insert([
			'title' => $data->title,
			'isbn' => $data->isbn,
			'pages' => $data->pages,
			'date' => $data->date,
			'language' => $data->language,
		]);

		$author = $this->db->table('author')->insert([
			'name' => $data->author
		]);

		$this->db->table('book_author')->insert([
			'book_id' => $book->id,
			'author_id' => $author->id
		]);
	}

	public function deleteBook(int $id): void
	{
		$this->db->table('book')->where('id', $id)->delete();
	}

	public function updateBook(\stdClass $data): void
	{
		$this->db->table('book')->where('id', (int)$data->id)->update([
			'title' => $data->title,
		]);
	}
}