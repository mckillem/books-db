<?php

declare(strict_types=1);

namespace App\Models;

use Nette\Database\Explorer;
use Nette\Database\Table\Selection;

class Language
{
	public Explorer $db;

	public function __construct(Explorer $db)
	{
		$this->db = $db;
	}

	public function getTable(): Selection
	{
		return $this->db->table('language');
	}

	public function getAllLanguages(): array
	{
		return $this->getTable()->fetchPairs('id', 'name');
	}

	public function getLanguageById(int $id): int
	{
		$language = $this->getTable()->get($id);

		return $language->id;
	}
}