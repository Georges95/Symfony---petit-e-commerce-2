<?php

namespace App\Controller;

use App\Entity\Products;
use App\Form\ProductType;
use App\Entity\Categories;
use App\Form\CategoriesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    #[Route('/product/add', name: 'app_productadd')]
    public function ajout(Request $request, EntityManagerInterface $entity): Response
    {
        $product = new Products();
        
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entity->persist($product);
            $entity->flush();
            $this->addFlash('success', 'Produit ajouté avec succès !');
            return $this->redirectToRoute('app_product');
        }
         /**
        * Formulaire pour categories
        */
        $category = new Categories();
        $formCategorie = $this->createForm(CategoriesType::class, $category);
        $formCategorie->handleRequest($request);
        if ($formCategorie->isSubmitted() && $formCategorie->isValid()) {
            $entity->persist($category);
            $entity->flush();
            $this->addFlash('success', 'Catégorie ajoutée avec succès !');
            return $this->redirectToRoute('app_productadd');
        }
                
        return $this->render('product/add.html.twig', [
            'form' => $form->createView(),
            'formCategorie' => $formCategorie,
        ]);
    }

   
    
    #[Route('/product', name: 'app_product')]
    public function index(Request $request, EntityManagerInterface $entity): Response
    {
        $product = $entity->getRepository(Products::class)->findAll();
        
       
        return $this->render('product/index.html.twig', [
            'products' => $product
            
        ]);
    }
}