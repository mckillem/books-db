<?php

declare(strict_types=1);

namespace App\Models;

use Nette\Database\Explorer;
use Nette\Database\Table\Selection;

class Genre
{
	public Explorer $db;

	public function __construct(Explorer $db)
	{
		$this->db = $db;
	}

	public function getTable(): Selection
	{
		return $this->db->table('genre');
	}

	public function getAllGenres(): array
	{
		return $this->getTable()->fetchPairs('id', 'name');
	}

	public function getGenreById(int $id): int
	{
		$genre = $this->getTable()->get($id);

		return $genre->id;
	}
}