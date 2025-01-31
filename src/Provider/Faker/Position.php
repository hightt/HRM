<?php

namespace App\Provider\Faker;

use Faker\Provider\Base;

class Position extends Base
{
	protected static $positions= [
		'Dyrektor generalny',
		'Dyrektor operacyjny',
		'Kierownik projektu',
		'Specjalista ds. administracji',
		'Asystent zarządu',
		'Programista',
		'Starszy programista',
		'Inżynier oprogramowania',
		'Specjalista ds. IT',
		'Administrator systemów',
		'DevOps Engineer',
		'Tester oprogramowania',
		'Architekt systemów',
		'Analityk danych',
		'Specjalista ds. bezpieczeństwa IT',
		'Specjalista ds. marketingu',
		'Kierownik ds. marketingu',
		'Specjalista ds. social media',
		'Kierownik ds. sprzedaży',
		'Przedstawiciel handlowy',
		'Specjalista ds. PR',
		'Copywriter',
		'Graphic Designer',
		'Księgowy',
		'Główny księgowy',
		'Specjalista ds. finansów',
		'Analityk finansowy',
		'Audytor wewnętrzny',
		'Doradca podatkowy',
		'Kierownik produkcji',
		'Inżynier produkcji',
		'Technik produkcji',
		'Magazynier',
		'Kierowca ciężarówki',
		'Logistyk',
		'Koordynator ds. transportu',
		'Lekarz',
		'Pielęgniarka',
		'Ratownik medyczny',
		'Fizjoterapeuta',
		'Dietetyk',
		'Technik farmacji',
		'Nauczyciel',
		'Wykładowca akademicki',
		'Bibliotekarz',
		'Psycholog szkolny',
		'Pedagog',
		'Inżynier budownictwa',
		'Architekt',
		'Kierownik budowy',
		'Operator koparki',
		'Elektryk',
		'Hydraulik',
		'Specjalista ds. obsługi klienta',
		'Rekruter',
		'HR Business Partner',
		'Trener personalny',
		'Stylista',
		'Fotograf',
		'Prawnik',
		'Adwokat',
		'Notariusz'
	];
	

	public static function position()
	{
		return static::randomElement(static::$positions);
	}
}
