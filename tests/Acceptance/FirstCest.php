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
		$I->amOnPage('/sign/in');
		$I->fillField('Email:', 'cms@itnetwork.cz');
		$I->fillField('Password:', 'itnetwork');
		$I->click('Přihlásit');

		$I->see('Přidej knihu');
		$I->click('Přidej knihu');

		$I->amOnPage('/home/edit');
		$I->fillField('Název:', 'Dědeček');
		$I->fillField('Autor:', 'Božena Čechová');
		$I->fillField('ISBN:', 'jdhfhfhfhsks');
		$I->fillField('Počet stran:', 432);
		$I->fillField('Datum:', 432);
		$I->selectOption('Jazyk:', '3');
		$I->checkOption('Přečteno:');
		$I->checkOption('Mám:');
		$I->fillField('Proč ji pořídít, o čem je, co jsem si zní odnesl:', 'nevím');
		$I->click('Přidat');

		$I->dontSee('Vložení knihy');
		$I->see('Kniha byla uložena');

		$I->fillField('text', 'Havlíčková');
		$I->click('Vyhledat knihu či autora');
		$I->see('Anna Havlíčková');
		$I->see('Atomic habits, Hledání knih');
    }
}
