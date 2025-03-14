<?php

declare(strict_types=1);

namespace App\Models;

use Nette\Database\Explorer;

class Author
{
	public Explorer $db;

	public function __construct(Explorer $db)
	{
		$this->db = $db;
	}

	public function getTable()
	{
		return $this->db->table('author');
	}

	public function getAllAuthors(): array
	{
		return $this->getTable()->fetchPairs('id', 'name');
	}

	public function getAuthor(string $text)
	{
		return $this->getTable()->where('name LIKE ?', '%' . $text . '%')->fetchAll();
	}
}