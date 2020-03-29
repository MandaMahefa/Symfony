<?php

namespace App\Controller;

use App\Entity\Property;
use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class HomeController extends  AbstractController {
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var PropertyRepository
     */
    private $propertyRepository;

    public function __construct(Environment $twig, PropertyRepository $propertyRepository)
    {
        $this->twig = $twig;
        $this->propertyRepository = $propertyRepository;
    }

    /**
     * @Route("/", name="home")
     * @return Response
     */
    public function index() : Response{
        $properties  = $this->propertyRepository->findLatest();
            return $this->render("Pages/home.html.twig",[
                "current_menu" => "home",
                "properties" => $properties
            ]);
    }

    /**
     * @Route("/biens/{slug}-{id}", name="property_show", requirements={"slug": "[a-z0-9\-]*"})
     * @param Property $property
     * @param string $slug
     * @return Response
     */
    public function show(Property $property, string $slug): Response
    {
        if ($property->getSlug() !== $slug){
            return $this->redirectToRoute('property_show', [
                'id' => $property->getId(),
                'slug' => $property->getSlug()
            ], 301);
        }
        return $this->render('property/show.html.twig',[
            "current_menu" => "properties",
            "property" => $property
        ]);
    }
}