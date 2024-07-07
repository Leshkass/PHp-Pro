<?php
declare(strict_types=1);

namespace App\Controller\CRUD;

use App\Entity\Car;
use App\Form\CarType;
use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(path: 'cars')]
class CarController extends AbstractController
{
    #[Route('/', name: 'app_crud_car_index', methods: 'GET')]
    public function index(
        CarRepository $cars,
        #[MapQueryParameter(filter: FILTER_VALIDATE_REGEXP, options: ['regexp' => '/^[1-9][0-9]*$/'])]
        int           $page = 1
    ): Response
    {
        return $this->render('crud/index.html.twig', [
            'cars' => $cars->findPage(max(1, $page))
        ]);
    }

    #[Route('/{id}/_delete', name: 'app_crud_delete_car', requirements: ['id' => Requirement::POSITIVE_INT])]
    public function delete(Car $car, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($car);
        $entityManager->flush();

        $this->addFlash('done', "Car {$car->getBrand()} deleted successfully");

        return $this->redirectToRoute('app_crud_car_index', [], Response::HTTP_SEE_OTHER);

    }

    #[Route('/{id}', name: 'app_crud_show_car', requirements: ['id' => Requirement::POSITIVE_INT])]
    public function show(Car $car): Response
    {
        return $this->render('crud/show.html.twig', ['car' => $car]);
    }

    #[Route('/new', name: 'app_crud_new_car', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CarType::class, $car = new Car());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($car);
            $entityManager->flush();

            $this->addFlash('done', "Car {$car->getBrand()} created successfully");

            return $this->redirectToRoute('app_crud_show_car', ['id' => $car->getId()]);
        }

        return $this->render('crud/new.html.twig', ['form' => $form]);
    }

    #[Route('/{id}/_update', name: 'app_crud_update_car', requirements: ['id' => Requirement::POSITIVE_INT],
        methods: ['GET', 'POST'])]
    public function update(Car $car, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('done', "Car {$car->getBrand()} update successfully");

            return $this->redirectToRoute('app_crud_show_car', ['id' => $car->getId()]);
        }

        return $this->render('crud/edit.html.twig', ['car' => $car, 'form' => $form]);
    }
}