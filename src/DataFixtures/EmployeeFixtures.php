<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Employee;
use App\Provider\Faker\Position;
use App\Repository\DepartmentRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class EmployeeFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private DepartmentRepository $departmentRepository,
        private UserRepository $userRepository,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $departments = $this->departmentRepository->findAll();
        $users = $this->userRepository->findAll();

        if ($departments === []) {
            throw new \RuntimeException('No departments found.');
        }

        if (count($users) < 101) {
            throw new \RuntimeException('At least 101 users are required.');
        }

        $faker = Factory::create('pl_PL');
        $positionProvider = new Position($faker);

        for ($i = 0; $i < 101; ++$i) {
            $employee = new Employee();

            $employee
                ->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setBirthdayDate(
                    $faker->dateTimeBetween('-60 years', '-18 years')
                )
                ->setPesel($faker->numerify('###########'))
                ->setEmploymentDate(
                    $faker->dateTimeBetween('-10 years', 'now')
                )
                ->setPosition($positionProvider->position())
                ->setPhoneNumber($faker->phoneNumber())
                ->setAddress($faker->address())
                ->setSalary($faker->numberBetween(4300, 10000))
                ->setStatus(true)
                ->setGender($faker->randomElement(['K', 'M']))
                ->setDepartment($departments[array_rand($departments)])
                ->setUser($users[$i]);

            $manager->persist($employee);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            DepartmentFixtures::class,
        ];
    }

    /**
     * @return list<string>
     */
    public static function getGroups(): array
    {
        return ['1'];
    }
}