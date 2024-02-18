<?php

namespace App\Controller;

use App\Entity\Student;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function test01(EntityManagerInterface $entityManager): Response
    {

        $student = new Student();
        $student->setName('Keyboard');
        $entityManager->persist($student);
        $entityManager->flush();

        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }

    #[Route('/test/{id}', name: 'app_test')]
    public function test02(StudentRepository $studentRepository, int $id): Response
    {
        $student = $studentRepository
            ->find($id);

        return new Response(
            '<html><body>student number: '. $student->getName() .'</body></html>'
        );
    }
}
