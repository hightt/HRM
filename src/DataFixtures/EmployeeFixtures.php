<?php
declare(strict_types = 1);

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use App\Entity\Employee;
use App\Provider\Faker\Position;
use App\Repository\DepartmentRepository;
use Faker\Provider\pl_PL\Address;
use Faker\Provider\pl_PL\PhoneNumber;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EmployeeFixtures extends Fixture implements DependentFixtureInterface 
{
    public function __construct(
        private DepartmentRepository $departmentRepository,
    )
    {}
    
    public function load(ObjectManager $manager): void
    {
        $departments = $this->departmentRepository->findAll();
        $numOfDepartments = count($departments);

        $generator = Factory::create("pl_PL");
        $faker = new Generator();
        $faker->addProvider(new PhoneNumber($faker));
        $faker->addProvider(new Address($faker));
        $faker->addProvider(new Position($faker));
        for ($i = 0; $i <= 100; $i++) {
            $employee = new Employee();
            $employee
                ->setFirstName($generator->firstName())
                ->setLastName($generator->lastName())
                ->setBirthdayDate($generator->dateTimeBetween('-60 years', '-18 years'))
                ->setPesel((string)rand(10000000000, 99999999999))
                ->setEmploymentDate($generator->dateTimeBetween('-10 years'))
                ->setPosition($faker->position())
                ->setPhoneNumber($faker->phoneNumber())
                ->setAddress($generator->address())
                ->setSalary($faker->numberBetween(4300, 10000))
                ->setStatus(true)
                ->setGender(rand(0, 1) === 0 ? 'K' : 'M')
                ->setDepartment($departments[rand(0, $numOfDepartments - 1)])
            ;
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
}
