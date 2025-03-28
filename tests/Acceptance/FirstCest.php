<?php

declare(strict_types=1);


namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

final class FirstCest
{
    public function _before(AcceptanceTester $I): void
    {
        // Code here will be executed before each test.
    }

    public function tryToTest(AcceptanceTester $I): void
    {
        // Write your tests here. All `public` methods will be executed as tests.
		$I->wantToTest('Login');
		$I->amOnPage('/sign/in');
		$I->fillField('Email:', 'cms@itnetwork.cz');
		$I->fillField('Password:', 'itnetwork');
		$I->click('Přihlásit');

		$I->see('Přidej knihu');
		$I->click('Přidej knihu');

		$I->wantToTest('Adding a book');
		$I->amOnPage('/home/edit');
		$I->fillField('Název:', 'Dědeček');
		$I->selectOption('Autor:', '3');
		$I->fillField('ISBN:', 'jdhfhfhfhsks');
		$I->fillField('Počet stran:', 432);
		$I->fillField('Datum:', 432);
		$I->selectOption('Jazyk:', '3');
		$I->checkOption('Přečteno:');
		$I->checkOption('Mám:');
		$I->fillField('Proč ji pořídít, o čem je, co jsem si zní odnesl:', 'nevím');
		$I->selectOption('Žánr:', '3');
		$I->click('Přidat');

		$I->dontSee('Vložení knihy');
		$I->see('Kniha byla uložena');

//todo: je potřeba zjistit jak stiskout enter a je otázka jestli datagrid potřebuju testovat
//		https://stackoverflow.com/questions/17082080/codeception-presskey-enter-does-not-work#26278808
		$I->wantToTest('Search for the author');
//		$I->fillField('filter[author]', 'Havlíčková');
//		$I->submitForm('#frm-grid-filter-filter-author', ['Havlíčková']);
//		$I->see('Anna Havlíčková');
//		$I->see('Atomic habits');
//		$I->see('Hledání knih');
//		$I->dontSee('Ultralearning');
    }
}
