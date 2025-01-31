<?php

namespace App\DataFixtures;

use App\Entity\Employee;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use App\Provider\Faker\Position;
use Faker\Provider\pl_PL\Address;
use Faker\Provider\pl_PL\PhoneNumber;

class EmployeeFixtures extends Fixture 
{
    public function load(ObjectManager $manager): void
    {
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
                ->setPesel(rand(10000000000, 99999999999))
                ->setEmploymentDate($generator->dateTimeBetween('-10 years'))
                ->setPosition($faker->position())
                ->setEmail($generator->email())
                ->setPhoneNumber($faker->phoneNumber())
                ->setAddress($generator->address())
                ->setSalary($faker->numberBetween(4300, 10000))
                ->setStatus(1)
                ->setGender(rand(0, 1) === 0 ? 'K' : 'M')
            ;
            $manager->persist($employee);
        }
        $manager->flush();
    }
}
