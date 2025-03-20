<?php

declare(strict_types=1);

namespace App\Models;

use Nette\Database\Explorer;
use Nette\Database\Table\Selection;

class Author
{
	public Explorer $db;

	public function __construct(Explorer $db)
	{
		$this->db = $db;
	}

	public function getTable(): Selection
	{
		return $this->db->table('author');
	}

	public function getAllAuthors(): array
	{
		return $this->getTable()->fetchPairs('id', 'name');
	}

	public function getAuthor(string $text): array
	{
		return $this->getTable()->where('name LIKE ?', '%' . $text . '%')->fetchAll();
	}
}