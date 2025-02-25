<?php
declare(strict_types = 1);

namespace App\DataFixtures;

use App\Entity\Department;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class DepartmentFixtures extends Fixture 
{
    public function load(ObjectManager $manager): void
    {
        $departments = [
            ['name' => 'HR', 'location' => 'Warszawa'],
            ['name' => 'IT', 'location' => 'Kraków'],
            ['name' => 'Marketing', 'location' => 'Gdańsk'],
            ['name' => 'Sprzedaż', 'location' => 'Wrocław'],
            ['name' => 'Finanse', 'location' => 'Poznań'],
            ['name' => 'Logistyka', 'location' => 'Łódź'],
            ['name' => 'Obsługa Klienta', 'location' => 'Katowice'],
            ['name' => 'Administracja', 'location' => 'Lublin'],
            ['name' => 'Badania i Rozwój', 'location' => 'Szczecin'],
            ['name' => 'Produkcja', 'location' => 'Bydgoszcz'],
            ['name' => 'Zakupy', 'location' => 'Rzeszów'],
            ['name' => 'Bezpieczeństwo', 'location' => 'Białystok'],
            ['name' => 'Kontrola Jakości', 'location' => 'Toruń'],
            ['name' => 'E-commerce', 'location' => 'Olsztyn'],
            ['name' => 'Księgowość', 'location' => 'Opole'],
            ['name' => 'Zarządzanie projektami', 'location' => 'Gdynia'],
            ['name' => 'Public Relations', 'location' => 'Częstochowa'],
            ['name' => 'Szkolenia', 'location' => 'Radom'],
            ['name' => 'Analiza Danych', 'location' => 'Sosnowiec'],
            ['name' => 'Infrastruktura IT', 'location' => 'Kielce'],
        ];
        

        foreach ($departments as $i => $data) {
            $department = new Department();
            $department->setName($data['name']);
            $department->setLocation($data['location']);
            $manager->persist($department);
        }

        $manager->flush();
    }


}
