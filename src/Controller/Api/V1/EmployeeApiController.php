<?php

declare(strict_types=1);

namespace App\Controller\Api\V1;

use DateTime;
use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/api/v1/employees')]
final class EmployeeApiController extends AbstractController
{
    #[Route(methods: [Request::METHOD_GET])]
    public function list(
        EmployeeRepository $employeeRepository,
    ): JsonResponse
    {
        $employees = $employeeRepository->findAll();

        $data = array_map(function (Employee $employee) {
            return [
                'id'                  => $employee->getId(),
                'firstName'           => $employee->getFirstName(),
                'lastName'            => $employee->getLastName(),
                'birthdayDate'        => $employee->getBirthdayDate()->format('Ymd'),
                'pesel'               => $employee->getPesel(),
                'employmentDate'      => $employee->getEmploymentDate(),
                'position'            => $employee->getPosition(),
                'phoneNumber'         => $employee->getPhoneNumber(),
                'address'             => $employee->getAddress(),
                'salary'              => $employee->getSalary(),
                'status'              => $employee->isStatus(),
                'gender'              => $employee->getGender(),
                'department'          => $employee->getDepartment()?->getName(),
                'contractType'        => $employee->getContractType(),
                'amountOfWorkingTime' => $employee->getAmountOfWorkingTime(),
            ];
        }, $employees);

        return $this->json($data);
    }

    #[Route(methods: [Request::METHOD_POST])]
    public function create(
        Request                $request, 
        EntityManagerInterface $em
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $employee = new Employee();
        $employee
            ->setFirstName($data['firstName'])
            ->setLastName($data['lastName'])
            ->setBirthdayDate(new DateTime($data['birthdayDate']))
            ->setPesel($data['pesel'])
            ->setEmploymentDate(new DateTime($data['employmentDate']))
            ->setPosition($data['position'])
            ->setPhoneNumber($data['phoneNumber'])
            ->setAddress($data['address'])
            ->setSalary($data['salary'])
            ->setStatus(true)
            ->setGender($data['gender'])
            ->setDepartment($data['department'])
            ->setContractType($data['contractType'])
            ->setAmountOfWorkingTime($data['amountOfWorkingTime'])
        ;

        $em->persist($employee);
        $em->flush();

        return $this->json([
            'id' => $employee->getId(),
            'message' => 'Employee created successfully',
        ], 201);
    }

    #[Route('/{id}', methods: [Request::METHOD_GET])]
    public function show(
        int                $id, 
        EmployeeRepository $employeeRepository
    ): JsonResponse
    {
        $employee = $employeeRepository->find($id);

        if (!$employee) {
            return $this->json(['message' => 'Employee not found'], 404);
        }

        return $this->json([
            'id'        => $employee->getId(),
            'name'      => $employee->getName(),
            'position'  => $employee->getPosition(),
            'hiredAt'   => $employee->getHiredAt(),
        ]);
    }

    #[Route('/{id}', methods: [Request::METHOD_PUT])]
    public function update(int $id, Request $request, EntityManagerInterface $em, EmployeeRepository $employeeRepository): JsonResponse
    {
        $employee = $employeeRepository->find($id);

        if (!$employee) {
            return $this->json(['message' => 'Employee not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $employee->setName($data['name'] ?? $employee->getName());
        $employee->setPosition($data['position'] ?? $employee->getPosition());
        if (isset($data['hiredAt'])) {
            $employee->setHiredAt(new \DateTime($data['hiredAt']));
        }

        $em->flush();

        return $this->json(['message' => 'Employee updated successfully']);
    }

    #[Route('/{id}', methods: [Request::METHOD_DELETE])]
    public function delete(
        int                    $id, 
        EntityManagerInterface $em, 
        EmployeeRepository     $employeeRepository
    ): JsonResponse
    {
        $employee = $employeeRepository->find($id);

        if (!$employee) {
            return $this->json(['message' => 'Employee not found'], 404);
        }

        $em->remove($employee);
        $em->flush();

        return $this->json(['message' => 'Employee deleted successfully']);
    }
}
