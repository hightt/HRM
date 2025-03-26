<?php
declare(strict_types = 1);

namespace App\DataFixtures;

use App\Entity\AbsenceSymbol;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class AbsenceSymbolFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $absenceSymbols = [
            ['name' => 'L',  'description' => 'Urlop wypoczynkowy – zaplanowany czas wolny przysługujący pracownikowi zgodnie z kodeksem pracy.'],
            ['name' => 'L4', 'description' => 'Chorobowe – nieobecność z powodu choroby, potwierdzona zwolnieniem lekarskim.'],
            ['name' => 'UZ', 'description' => 'Usprawiedliwiona nieobecność – nieobecność zaakceptowana przez pracodawcę z ważnych powodów osobistych.'],
            ['name' => 'NZ', 'description' => 'Nieusprawiedliwiona nieobecność – absencja bez wcześniejszego zgłoszenia lub akceptacji pracodawcy.'],
            ['name' => 'SZ', 'description' => 'Szkolenie – czas przeznaczony na rozwój zawodowy, udział w kursach lub konferencjach.'],
            ['name' => 'OP', 'description' => 'Opieka nad dzieckiem – zwolnienie na opiekę nad chorym dzieckiem lub innym członkiem rodziny.'],
            ['name' => 'UŻ', 'description' => 'Urlop na żądanie – krótkoterminowy urlop przyznawany na wniosek pracownika, maksymalnie 4 dni w roku.'],
            ['name' => 'WS', 'description' => 'Wyjazd służbowy – delegacja lub podróż związana z wykonywaniem obowiązków służbowych.'],
            ['name' => 'MC', 'description' => 'Macierzyński – urlop przysługujący matce po urodzeniu dziecka.'],
            ['name' => 'BH', 'description' => 'Badania lekarskie – czas poświęcony na obowiązkowe badania medyczne wymagane przez pracodawcę.'],
        ];

        foreach ($absenceSymbols as $i => $absenceSymbol) {
            $department = new AbsenceSymbol();
            $department->setName($absenceSymbol['name']);
            $department->setDescription($absenceSymbol['description']);
            $manager->persist($department);
        }

        $manager->flush();
    }


    public static function getGroups(): array
    {
        return ['0'];
    }


}
