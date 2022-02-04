<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Customer;
use App\Repository\CustomerRepository;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 **/
class ProductController extends AbstractController
{

    private $productRepository;

    public function __construct(ProductRepository $productRepository, CustomerRepository $customerRepository)
    {
        $this->productRepository = $productRepository;
        $this->customerRepository = $customerRepository;
    }

    /**
     * @Route("/product", name="product")
     */
    public function index(): Response
    {
        $data = $this->productRepository->findAll();

        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'data' => $data
        ]);
    }

    /**
     * @Route("/product/new" , name="new_product")
     */
    public function showNewproduct(Request $request, ValidatorInterface $validator)
    {
        $msg = $request->query->get('msg');
        $product = new Product;
        $form = $this->createFormBuilder($product)
            ->add('name', TextType::class, [
                'label' => 'Product Name',
                'empty_data' => '',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cate')
                        ->orderBy('cate.name', 'ASC');
                },
                // "empty_data"  => new ArrayCollection,
                'choice_label' => 'name',
                'expanded' => false,
                'label' => 'Select Category',
                'attr' => ['class' => 'form-select']
            ])
            ->add('customer', EntityType::class, [
                'class' => Customer::class,
                // 'query_builder' => function (EntityRepository $er) {
                //     return $er->createQueryBuilder('p')
                //         ->orderBy('p.email', 'ASC');
                // },
                // "empty_data"  => new ArrayCollection,
                'choice_label' => 'email',
                'expanded' => false,
                'multiple' => true,
                'label' => 'Select Cutomer',
                // 'mapped' => false,
                'attr' => ['class' => 'form-select'],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save',
                'attr' => ['class' => 'btn btn-primary mx-2'],
            ])
            ->add('cancel', ButtonType::class, [
                'label' => 'Cancel',
                'attr' => ['class' => 'btn btn-secondary', 'onClick' => 'window.location.href="/product"'],
            ])
            ->setMethod('POST')
            // ->setAction($this->generateUrl("update_product", ['id' => $data['id']]))
            ->getForm();


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $form = $form->getData();
            empty(trim($form->getName())) ? true : $product->setName($form->getName());
            empty($form->getCategory()) ? true : $product->setCategory($form->getCategory());
            empty($form->getCustomer()) ? true : $product->setCustomer($form->getCustomer());
            $errors = $validator->validate($product);
            $updateproduct  = $this->productRepository->saveproduct($product);
            return $this->redirectToRoute('product', ['msg' => 'Product Created Successfully']);
        }
        return $this->render('product/add.html.twig', [
            'form' => $form->createView()
        ]);
        // return $this->render('customer/add.html.twig', [
        //     'controller_name' => 'CustomerController',
        //     'msg' => $msg
        // ]);
    }

    /**
     * @Route("/product/{id}/edit", name="edit_product", methods="GET|PUT")
     */
    public function editproduct($id, Request $request, ValidatorInterface $validator)
    {
        $product = $this->productRepository->findOneBy(['id' => $id]);
        $data = [];

        if ($product) {
            $data = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'category' => $product->getCategory()->getId()
            ];
        } else {
            throw new NotFoundHttpException('No Record Found');
        }

        $form = $this->createFormBuilder($product)
            ->add('name', TextType::class, [
                'label' => 'Product Name',
                'data' => $data['name'],
                'empty_data' => '',
                'attr' => ['class' => 'form-control']
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cate')
                        ->orderBy('cate.name', 'ASC');
                },
                // "empty_data"  => new ArrayCollection,
                'choice_label' => 'name',
                'expanded' => false,
                'label' => 'Select Category',
                'attr' => ['class' => 'form-select']
            ])
            ->add('customer', EntityType::class, [
                'class' => Customer::class,
                // 'query_builder' => function (EntityRepository $er) {
                //     return $er->createQueryBuilder('p')
                //         ->orderBy('p.email', 'ASC');
                // },
                // "empty_data"  => new ArrayCollection,
                'choice_label' => 'email',
                'expanded' => false,
                'multiple' => true,
                'label' => 'Select Cutomer',
                // 'mapped' => false,
                'attr' => ['class' => 'form-select'],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Update Product',
                'attr' => ['class' => 'btn btn-primary mx-2']
            ])
            ->add('cancel', ButtonType::class, [
                'label' => 'Cancel',
                'attr' => ['class' => 'btn btn-secondary mx-2', 'onClick' => 'window.location.href="/product"']
            ])
            ->setMethod('PUT')
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $form = $form->getData();
            empty(trim($form->getName()))  ? true : $product->setName($form->getName());
            empty($form->getCustomer()) ? true : $product->setCustomer($form->getCustomer());

            // $customers = $form->getCustomer();
            // if (!empty($customers)) {
            //     foreach ($customers as $custo) {
            //         $cst = $this->customerRepository->findOneBy(['id' => $custo->getId()]);
            //         $product->setCustomer($cst);
            //     }
            // }


            $errors = $validator->validate($product);
            $updateproduct = $this->productRepository->updateProduct($product);
            return $this->redirectToRoute('edit_product', ['id' => $form->getId(), 'msg' => 'Product Updated Successfully.']);
        }

        return $this->render('product/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/product/{id}", name="delete_product", methods="DELETE")
     */
    public function deleteProduct($id)
    {
        $product = $this->productRepository->findOneBy(['id' => $id]);
        $deleteproduct = $this->productRepository->removeProduct($product);
        return $this->redirectToRoute('product', ['msg' => 'Product Deleted Successfully']);
    }
}
