<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
// use Doctrine\DBAL\Types\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 **/
// class CustomerController extends ServiceEntityRepository
class CustomerController extends AbstractController
{
    private $customerRepository;
    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }
    /**
     * @Route("/customers", name="customer" ,methods="GET")
     */
    public function index(): Response
    {
        $data = $this->customerRepository->findAll();
        return $this->render('customer/index.html.twig', [
            'controller_name' => 'CustomerController',
            'data' => $data,
        ]);
    }

    /**
     * @Route("/customers/new/", name="new_customer" , methods="GET|POST")
     */
    public function showNewCustomer(Request $request, ValidatorInterface $validator)
    {
        $msg = $request->query->get('msg');
        $customer = new Customer;
        $form = $this->createFormBuilder($customer)
            ->add('firstName', TextType::class, [
                'label' => 'First Name',
                'empty_data' => '',
                'attr' => ['class' => 'form-control'],

            ])
            ->add('lastName', TextType::class, [
                'label' => 'Last Name',
                'empty_data' => '',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'empty_data' => '',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => 'Phone Number',
                'empty_data' => '',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('dob', TextType::class, [
                'label' => 'Date Of Birth',
                'empty_data' => '',
                'attr' => ['class' => 'form-control']
            ])
            ->add('gender', ChoiceType::class, [
                'label' => 'Gender',
                'choices' => [
                    'Male' => 'Male',
                    'Female' => 'Female'
                ],
                'expanded' => true,
                'empty_data' => '',
                'attr' => ['class' => 'form-check']
            ])
            ->add('hobbies', ChoiceType::class, [
                'label' => 'Hobbies',
                'choices' => [
                    'Cricket' => 'Cricket',
                    'FootBall' => 'FootBall',
                    'VolleyBall' => 'VolleyBall',
                    'Hocky' => 'Hocky',
                ],
                'expanded' => false,
                'multiple' => true,
                'empty_data' => '',
                'attr' => ['class' => 'form-control']
            ])
            ->add('address', TextareaType::class, [
                'label' => 'Address',
                'empty_data' => '',
                'attr' => ['class' => 'form-control']
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save',
                'attr' => ['class' => 'btn btn-primary mx-2'],
            ])
            ->add('cancel', ButtonType::class, [
                'label' => 'Cancel',
                'attr' => ['class' => 'btn btn-secondary', 'onClick' => 'window.location.href="/customers"'],
            ])
            ->setMethod('POST')
            // ->setAction($this->generateUrl("update_customer", ['id' => $data['id']]))
            ->getForm();


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$customer` variable will be updated
            $form = $form->getData();
            empty(trim($form->getFirstName())) ? true : $customer->setFirstName($form->getFirstName());
            empty(trim($form->getLastName())) ? true : $customer->setLastName($form->getLastName());
            empty(trim($form->getEmail())) ? true : $customer->setEmail($form->getEmail());
            empty(trim($form->getPhoneNumber())) ? true : $customer->setPhoneNumber($form->getPhoneNumber());
            empty($form->getDob()) ? true : $customer->setDob($form->getDob());
            empty($form->getGender()) ? true : $customer->setGender($form->getGender());
            empty($form->getHobbies()) ? true : $customer->setHobbies(implode(',', $form->getHobbies()));
            empty($form->getAddress()) ? true : $customer->setAddress($form->getAddress());


            $errors = $validator->validate($customer);
            $updateCustomer  = $this->customerRepository->saveCustomer($customer);
            return $this->redirectToRoute('customer', ['msg' => 'Customer Created Successfully']);
        }
        return $this->render('customer/add.html.twig', [
            'form' => $form->createView()
        ]);
        // return $this->render('customer/add.html.twig', [
        //     'controller_name' => 'CustomerController',
        //     'msg' => $msg
        // ]);
    }
    /**
     * @Route("/customers/{id}", name="get_one_customer" , methods={"GET"})
     */
    public function getCustomer($id)
    {
        $customer = $this->customerRepository->findOneBy(['id' => $id]);
        $data = 'No Record Found';
        if ($customer) {
            $data = [
                'id' => $customer->getId(),
                'firstName' => $customer->getFirstName(),
                'lastName' => $customer->getLastName(),
                'email' => $customer->getEmail(),
                'phoneNumber' => $customer->getPhoneNumber()
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }
    /**
     * @Route("/customers", name="get_all_customer" , methods="GET")
     */
    public function getAll(): JsonResponse
    {
        $customers = $this->customerRepository->findAll();
        $data = [];

        foreach ($customers as $customer) {
            $data[] = [
                'id' => $customer->getId(),
                'firstName' => $customer->getFirstName(),
                'lastName' => $customer->getLastName(),
                'email' => $customer->getEmail(),
                'phoneNumber' => $customer->getPhoneNumber(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }
    /**
     * @Route("/customers/{id}", name="delete_customer", methods="DELETE")
     */
    public function delete_customer($id)
    {
        $customer = $this->customerRepository->findOneBy(['id' => $id]);
        $deleteCustomer = $this->customerRepository->removeCustomer($customer);
        return $this->redirectToRoute('customer', ['msg' => 'Customer Deleted Successfully']);
        // return new JsonResponse(['status' => 'Customer deleted!'], Response::HTTP_OK);
    }

    /**
     * @Route("/customers/{id}/edit", name="edit_customer" , methods="GET|PUT")
     */
    public function showEditCustomer($id, Request $request, ValidatorInterface $validator)
    {
        $customer = $this->customerRepository->findOneBy(['id' => $id]);
        $data = [];
        if ($customer) {
            $data = [
                'id' => $customer->getId(),
                'firstName' => $customer->getFirstName(),
                'lastName' => $customer->getLastName(),
                'email' => $customer->getEmail(),
                'phoneNumber' => $customer->getPhoneNumber(),
                'dob' => $customer->getDob(),
                'gender' => $customer->getGender(),
                'hobbies' =>  empty($customer->getHobbies()) ?: explode(',', $customer->getHobbies()),
                // 'hobbies' =>  "['" . str_replace(",", "','", $customer->getHobbies()) . "']",
                'address' => $customer->getAddress(),
            ];
        }
        if (empty($data)) {
            throw new NotFoundHttpException('No Record Found');
        }

        $form = $this->createFormBuilder($customer)
            ->add('firstName', TextType::class, [
                'label' => 'First Name',
                'data' => $data['firstName'],
                'empty_data' => '',
                'attr' => ['class' => 'form-control'],

            ])
            ->add('lastName', TextType::class, [
                'label' => 'Last Name',
                'data' => $data['lastName'],
                'empty_data' => '',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'data' => $data['email'],
                'empty_data' => '',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => 'Phone Number',
                'data' => $data['phoneNumber'],
                'empty_data' => '',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('dob', TextType::class, [
                'label' => 'Date Of Birth',
                'empty_data' => '',
                'data' => $data['dob'],
                'attr' => ['class' => 'form-control']
            ])
            ->add('gender', ChoiceType::class, [
                'label' => 'Gender',
                'choices' => [
                    'Male' => 'Male',
                    'Female' => 'Female'
                ],
                'data' => $data['gender'],
                'expanded' => true,
                'empty_data' => '',
                'attr' => ['class' => 'form-check']
            ])
            ->add('hobbies', ChoiceType::class, [
                'label' => 'Hobbies',
                'choices' => [
                    'Cricket' => 'Cricket',
                    'FootBall' => 'FootBall',
                    'VolleyBall' => 'VolleyBall',
                    'Hocky' => 'Hocky',
                ],
                'expanded' => false,
                'multiple' => true,
                // 'empty_data' => array(),
                'data' => array_values($data['hobbies']),
                'attr' => ['class' => 'form-control']
            ])
            ->add('address', TextareaType::class, [
                'label' => 'Address',
                'empty_data' => '',
                'data' => $data['address'],
                'attr' => ['class' => 'form-control']
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Update',
                'attr' => ['class' => 'btn btn-primary mx-2'],
            ])
            ->add('cancel', ButtonType::class, [
                'label' => 'Cancel',
                'attr' => ['class' => 'btn btn-secondary', 'onClick' => 'window.location.href="/customers"'],
            ])
            ->setMethod('PUT')
            // ->get('hobbies')->resetViewTransformers()
            // ->setAction($this->generateUrl("update_customer", ['id' => $data['id']]))
            ->getForm();


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$customer` variable will be updated
            $form = $form->getData();

            empty(trim($form->getFirstName())) ? true : $customer->setFirstName($form->getFirstName());
            empty(trim($form->getLastName())) ? true : $customer->setLastName($form->getLastName());
            empty(trim($form->getEmail())) ? true : $customer->setEmail($form->getEmail());
            empty(trim($form->getPhoneNumber())) ? true : $customer->setPhoneNumber($form->getPhoneNumber());
            empty($form->getDob()) ? true : $customer->setDob($form->getDob());
            empty($form->getGender()) ? true : $customer->setGender($form->getGender());
            empty($form->getHobbies()) ? true : $customer->setHobbies(implode(',', $form->getHobbies()));
            empty($form->getAddress()) ? true : $customer->setAddress($form->getAddress());
            $errors = $validator->validate($customer);

            $updateCustomer  = $this->customerRepository->updateCustomer($customer);
            // return $this->redirectToRoute('customer');
            return $this->redirectToRoute('edit_customer', ['id' => $form->getId(), 'msg' => 'Customer Updated Successfully']);
        }
        // return new JsonResponse($data, Response::HTTP_OK);

        return $this->render('customer/edit.html.twig', [
            'form' => $form->createView()
        ]);

        // return $this->render('customer/edit.html.twig', [
        //     'controller_name' => 'CustomerController',
        //     'data' => $data,
        // ]);
    }
}
