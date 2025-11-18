<?php

namespace App\Controller;


use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Coche;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CocheFormType as CocheType;



final class PageController extends AbstractController
{   
    #[Route('/', name: 'inicio')]
    public function inicio(ManagerRegistry $doctrine): Response
{
    // Comprobamos si el usuario está logeado
    if (!$this->getUser()) {
        return $this->redirectToRoute('login'); 
    }

    // Obtenemos todos los coches
    $coches = $doctrine->getRepository(Coche::class)->findAll();

    // Mostramos la plantilla con la lista
    return $this->render('inicio.html.twig', [
        'coches' => $coches,
    ]);
}


    #[Route('/coche/nuevo', name: 'nuevo')]
    public function nuevo(ManagerRegistry $doctrine, Request $request) {
        $coche = new Coche();
        $formulario = $this->createForm(CocheType::class, $coche);
        $formulario->handleRequest($request);

        if ($formulario->isSubmitted() && $formulario->isValid()) {
            $contacto = $formulario->getData();
            
            $entityManager = $doctrine->getManager();
            $entityManager->persist($coche);
            $entityManager->flush();
            return $this->redirectToRoute('ficha_coche', ["codigo" => $coche->getId()]);
        }
        return $this->render('nuevo.html.twig', array(
            'formulario' => $formulario->createView()
        ));
    }


    #[Route('/coche/editar/{codigo}', name: 'editar', requirements:["codigo"=>"\d+"])]
    public function editar(ManagerRegistry $doctrine, Request $request, int $codigo) {
        $repositorio = $doctrine->getRepository(Coche::class);
        //En este caso, los datos los obtenemos del repositorio de contactos
        $coche = $repositorio->find($codigo);
        if ($coche){
            $formulario = $this->createForm(CocheType::class, $coche);

            $formulario->handleRequest($request);

            if ($formulario->isSubmitted() && $formulario->isValid()) {
                //Esta parte es igual que en la ruta para insertar
                $coche = $formulario->getData();
                $entityManager = $doctrine->getManager();
                $entityManager->persist($coche);
                $entityManager->flush();
                return $this->redirectToRoute('ficha_coche', ["codigo" => $coche->getId()]);
            }
            return $this->render('nuevo.html.twig', array(
                'formulario' => $formulario->createView()
            ));
        }else{
            return $this->render('ficha_coche.html.twig', [
                'coche' => NULL
            ]);
        }
    }


    #[Route("/coche/delete/{id}", name:'eliminar_contacto')]

    public function delete(ManagerRegistry $doctrine, $id): Response{
        $entityManager = $doctrine->getManager();
        $repositorio = $doctrine->getRepository(Coche::class);
        $coche = $repositorio->find($id);
        if ($coche){
            try
            {
                $entityManager->remove($coche);
                $entityManager->flush();
                return $this->redirectToRoute('inicio');
            } catch (\Exception $e) {
                return new Response("Error eliminado objeto");
            }
        }else
            return $this->render('ficha_coche.html.twig', [
                'coche' => null
            ]);
    }


    #[Route('/coche/{codigo}', name: 'ficha_coche')]

    public function ficha (ManagerRegistry $doctrine, $codigo): Response{
        $repositorio = $doctrine->getRepository(Coche::class);
        $coche = $repositorio->find($codigo);

        return $this->render('ficha_coche.html.twig', [
            'coche' => $coche
        ]);
    }
}
