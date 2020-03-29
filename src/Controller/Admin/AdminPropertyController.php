<?php
namespace App\Controller\Admin;


use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPropertyController extends AbstractController{


    /**
     * @var PropertyRepository
     */
    private $propertyRepository;
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * AdminPropertyController constructor.
     * @param PropertyRepository $propertyRepository
     */
    public function __construct(PropertyRepository $propertyRepository)
    {
        $this->propertyRepository = $propertyRepository;
    }

    /**
     * @Route("/admin/property/new", name="admin.property.new")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response {
        $property =  new Property();
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->getDoctrine()->getManager()->persist($property);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("success","Le bien a été bien ajouté.");
            return $this->redirectToRoute('admin.property.index');
        }
        return $this->render('admin/property/new.html.twig', [
            "properties" => $property,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin", name="admin.property.index")
     * @return Response
     */
    public function index(): Response {
        $properties =  $this->propertyRepository->findAll();
        return $this->render('admin/property/index.html.twig', [
            "properties" => $properties
        ]);
    }

    /**
     * @Route("/admin/delete/{id}", name="admin.property.delete")
     * @param Property $property
     * @param Request $request
     * @return Response
     */
    public function delete(Property $property, Request $request): Response {
        if ($this->isCsrfTokenValid('delete'.$property->getId(), $request->get('_token'))){
            $property = $this->propertyRepository->find($property->getId());
            $this->getDoctrine()->getManager()->remove($property);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("success","Le bien a été bien supprimé.");
        }
        return $this->redirectToRoute('admin.property.index');
    }

    /**
     * @Route("/admin/{id}/edit", name="admin.property.edit")
     * @param Property $property
     * @param Request $request
     * @return Response
     */
    public function edit(Property $property, Request $request) {
        $form = $this->createForm(PropertyType::class, $property);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("success","Le bien a été bien modifié.");
            return $this->redirectToRoute('admin.property.index');

        }
        return $this->render('admin/property/edit.html.twig',[
            "property" => $property,
            "form" => $form->createView()
        ]);
    }
}