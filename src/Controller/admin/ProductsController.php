<?php

namespace App\Controller\admin;

use App\Entity\Products;
use App\Form\ProductsFormType;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Config\Framework\RequestConfig;

#[Route('/admin/produits', name: 'admin_products_')]
class ProductsController extends AbstractController
{
    #[Route('/',name:'index')]
    public function index(ProductsRepository $productsRepository): Response
    {

        return $this->render('admin/products/index.html.twig',[
            'product' => $productsRepository->findAll()
        ]);
    }


    #[Route('/ajout',name:'add')]
    public function add(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $product = new Products();
        $productForm = $this->createForm(ProductsFormType::class, $product);
        $productForm->handleRequest($request);
        if($productForm->isSubmitted() && $productForm->isValid()){
            $slug = $slugger->slug($product->getName());
                $product->setSlug($slug);
                $prix = $product->getPrice() * 100;
                $product->setPrice($prix);
                $em->persist($product);
                $em->flush();
                $this->addFlash('success', 'Produit ajouté avec succés');
                return $this->redirectToRoute('admin_products_index');
        }


        return $this->render('admin/products/add.html.twig',[
            'productForm' => $productForm->createView()
        ]);
    }
    #[Route('/edition/{id}',name:'edit')]
    public function edit(Products $product,Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('PRODUCT_EDIT', $product);
        $prix = $product->getPrice() / 100 ;
        $product->setPrice($prix);
        $productForm = $this->createForm(ProductsFormType::class, $product);
        $productForm->handleRequest($request);
        if($productForm->isSubmitted() && $productForm->isValid()){
            $slug = $slugger->slug($product->getName());
            $product->setSlug($slug);
            $prix = $product->getPrice() * 100;
            $product->setPrice($prix);
            $em->persist($product);
            $em->flush();
            $this->addFlash('success', 'Produit modifier avec succés');


            return $this->redirectToRoute('admin_products_index');
        }


        return $this->render('admin/products/edit.html.twig',[
            'productForm' => $productForm->createView()
        ]);

        //return $this->render('admin/products/index.html.twig');
    }
    #[Route('/supression/{id}',name:'delete')]
    public function delete(EntityManagerInterface $manager, Products $products): Response
    {
        $manager->remove($products);
        $manager->flush();
        $this->addFlash('success', 'Produit suprimé avec succés');
        return $this->redirectToRoute('admin_products_index');
    }
}