<?php

namespace App\Controller;

use App\Repository\CompanyRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Company;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\FormFactoryInterface;
use App\Form\CompanyType;

#[Route('/company')]
class CompanyController extends AbstractController
{

    public function __construct( private CompanyRepository $companyRepository,
        private EntityManagerInterface $entityManager,
        private FormFactoryInterface $formFactory




    ){}

    
    #[Route('/', name: 'company_index')]
    public function index( Request $request): Response
    {
        
        $companies = $this->companyRepository->findAll();
        dump($request);



        return $this->render('company/index.html.twig', [
            'companies' => $companies,
        ]);
    }

    #[Route('/add', name: 'company_add1')]
    public function addFirst(Request $request): Response
    {
        
        $company = new Company();
        
        $form = $this->formFactory->create(CompanyType::class, $company);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($company);
            $this->entityManager->flush();

            return $this->redirectToRoute('company_index');
        }

        return $this->render('company/create.html.twig', ['form' => $form->createView()]);

       
    }

    #[Route('/update{id}', name: 'company_update')]
    public function update( int $id ): Response{
        
        $company = $this->$companyRepository->find($id);

        $company->setName("TC Bois");
        $this->$entityManager->persist($company);
        $this->$entityManager->flush();

        return new response("Entreprise renommée avec succès ! ");

        

    }

    #[Route('/delete/{id}', name: 'company_delete')]
    public function deleteCompany(int $id,EntityManagerInterface $entityManager): Response
    {
        
        $company = $this->$companyRepository->find($id);

        //$entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($company);
        $entityManager ->flush();

        return new Response("Entreprise supprimer avec succès !")
        ;
    }
    
}