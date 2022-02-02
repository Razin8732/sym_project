<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 **/
class CategoryController extends AbstractController
{

    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @Route("/category", name="category")
     */
    public function index(): Response
    {
        $data = $this->categoryRepository->findAll();
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'data' => $data
        ]);
    }

    /**
     * @Route("/category/new" , name="new_category")
     */
    public function showNewCategory(Request $request, ValidatorInterface $validator)
    {
        $msg = $request->query->get('msg');
        $category = new Category;
        $form = $this->createFormBuilder($category)
            ->add('name', TextType::class, [
                'label' => 'Category Name',
                'empty_data' => '',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save',
                'attr' => ['class' => 'btn btn-primary mx-2'],
            ])
            ->add('cancel', ButtonType::class, [
                'label' => 'Cancel',
                'attr' => ['class' => 'btn btn-secondary', 'onClick' => 'window.location.href="/categorys"'],
            ])
            ->setMethod('POST')
            // ->setAction($this->generateUrl("update_category", ['id' => $data['id']]))
            ->getForm();


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $form = $form->getData();
            empty(trim($form->getName())) ? true : $category->setName($form->getName());
            $errors = $validator->validate($category);
            $updatecategory  = $this->categoryRepository->saveCategory($category);
            return $this->redirectToRoute('category', ['msg' => 'category Created Successfully']);
        }
        return $this->render('category/add.html.twig', [
            'form' => $form->createView()
        ]);
        // return $this->render('customer/add.html.twig', [
        //     'controller_name' => 'CustomerController',
        //     'msg' => $msg
        // ]);
    }

    /**
     * @Route("/category/{id}/edit", name="edit_category", methods="GET|PUT")
     */
    public function editCategory($id, Request $request, ValidatorInterface $validator)
    {
        $category = $this->categoryRepository->findOneBy(['id' => $id]);
        $data = [];

        if ($category) {
            $data = [
                'id' => $category->getId(),
                'name' => $category->getName()
            ];
        } else {
            throw new NotFoundHttpException('No Record Found');
        }

        $form = $this->createFormBuilder($category)
            ->add('name', TextType::class, [
                'label' => 'Category Name',
                'data' => $data['name'],
                'empty_data' => '',
                'attr' => ['class' => 'form-control']
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Update Category',
                'attr' => ['class' => 'btn btn-primary mx-2']
            ])
            ->add('cancel', ButtonType::class, [
                'label' => 'Cancel',
                'attr' => ['class' => 'btn btn-secondary mx-2']
            ])
            ->setMethod('PUT')
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $form = $form->getData();
            empty(trim($form->getName()))  ? true : $category->setName($form->getName());

            $errors = $validator->validate($category);
            $updatecategory = $this->categoryRepository->updateCategory($category);
            return $this->redirectToRoute('edit_category', ['id' => $form->getId(), 'msg' => 'Category Updated Successfully.']);
        }

        return $this->render('category/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/category/{id}", name="delete_category", methods="DELETE")
     */
    public function deleteCategory($id)
    {
        $category = $this->categoryRepository->findOneBy(['id' => $id]);
        $deleteCategory = $this->categoryRepository->removeCategory($category);
        return $this->redirectToRoute('category', ['msg' => 'Category Deleted Successfully']);
    }
    /**
     * @Route("/category/products/{id}", name="category_all_product", methods="POST")
     */
    public function categoryAllProduct($id, Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        if ($request->isXmlHttpRequest()) {
            $category = $doctrine->getRepository(Category::class)->find($id);
            $products = $category->getProducts();
            $data['category'] = $category->getName();
            foreach ($products as $product) {
                $data['products'][] =  $product->getName();
            }

            return new JsonResponse($data, Response::HTTP_OK);
        }
        return new JsonResponse('Not Allowed', Response::HTTP_OK);
    }
}
