<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\ConstraintViolation;

use Symfony\Component\Filesystem\Filesystem;

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
        $images_directory = $this->getParameter('images_directory');
        return $this->render('customer/index.html.twig', [
            'controller_name' => 'CustomerController',
            'data' => $data,
            'images_directory' => $images_directory
        ]);
    }

    /**
     * @Route("/customers/new/", name="new_customer" , methods="GET|POST")
     */
    public function showNewCustomer(Request $request, ValidatorInterface $validator, SluggerInterface $slugger)
    {
        $msg = $request->query->get('msg');
        $customer = new Customer;
        $form = $this->createForm(CustomerType::class, $customer, [
            'method' => 'POST',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $form_data = $form->getData();
            $imageFile = $form_data->getImage();
            if (empty(trim($imageFile))) {
                $error = new FormError("Please upload a valid image file");
                $form->get('image')->addError($error);
            }
            if ($form->isValid()) {

                // $form->getData() holds the submitted values
                // but, the original `$customer` variable will be updated
                // $form_data = $form->getData();
                empty(trim($form_data->getFirstName())) ? true : $customer->setFirstName($form_data->getFirstName());
                empty(trim($form_data->getLastName())) ? true : $customer->setLastName($form_data->getLastName());
                empty(trim($form_data->getEmail())) ? true : $customer->setEmail($form_data->getEmail());
                empty(trim($form_data->getPhoneNumber())) ? true : $customer->setPhoneNumber($form_data->getPhoneNumber());
                empty($form_data->getDob()) ? true : $customer->setDob($form_data->getDob());
                empty($form_data->getGender()) ? true : $customer->setGender($form_data->getGender());
                empty($form_data->getHobbies()) ? true : $customer->setHobbies(implode(',', $form_data->getHobbies()));
                empty($form_data->getAddress()) ? true : $customer->setAddress($form_data->getAddress());
                empty($form_data->getProduct()) ? true : $customer->setProduct($form_data->getProduct());

                /** @var UploadedFile $imageFile */
                $imageFile = $form_data->getImage();

                if ($imageFile) {
                    $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                    try {
                        $imageFile->move(
                            $this->getParameter('images_directory'),
                            $newFilename
                        );
                        // throw new FileException('error in file upload');
                    } catch (FileException $e) {
                        // Exeption Occured
                        $error = new FormError("Somthing went wrong while uploading image ");
                        $form->get('image')->addError($error);
                        return $this->render('customer/add.html.twig', [
                            'form' => $form->createView(),
                        ]);
                    }
                }
                empty($newFilename) ? true : $customer->setImage($newFilename);
                $errors = $validator->validate($customer);
                if (empty(trim($imageFile))) {
                    $error = new FormError("Please upload a valid image file");
                    $form->get('image')->addError($error);
                    return $this->render('customer/add.html.twig', [
                        'form' => $form->createView(),
                        'errors' => $errors
                    ]);
                }

                // $errors = $validator->validate($customer);
                $updateCustomer  = $this->customerRepository->saveCustomer($customer);
                return $this->redirectToRoute('customer', ['msg' => 'Customer Created Successfully']);
            }
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
        $images_directory = $this->getParameter('images_directory');
        $deleteCustomer = $this->customerRepository->removeCustomer($customer, $images_directory);
        return $this->redirectToRoute('customer', ['msg' => 'Customer Deleted Successfully']);
        // return new JsonResponse(['status' => 'Customer deleted!'], Response::HTTP_OK);
    }

    /**
     * @Route("/customers/{id}/edit", name="edit_customer" , methods="GET|PUT")
     */
    public function showEditCustomer($id, Request $request, ValidatorInterface $validator, SluggerInterface $slugger)
    {
        $customer = $this->customerRepository->findOneBy(['id' => $id]);
        $data = [];
        if ($customer) {
            $data = [
                'image' => $customer->getImage(),
            ];
        }
        //     $data = [
        //         'id' => $customer->getId(),
        //         'firstName' => $customer->getFirstName(),
        //         'lastName' => $customer->getLastName(),
        //         'email' => $customer->getEmail(),
        //         'phoneNumber' => $customer->getPhoneNumber(),
        //         'dob' => $customer->getDob(),
        //         'gender' => $customer->getGender(),
        //         'hobbies' =>  empty($customer->getHobbies()) ?: explode(',', $customer->getHobbies()),
        //         // 'hobbies' =>  "['" . str_replace(",", "','", $customer->getHobbies()) . "']",
        //         'address' => $customer->getAddress(),
        //         'image' => $customer->getImage(),
        //     ];
        // }
        if (empty($customer)) {
            throw new NotFoundHttpException('No Record Found');
        }
        $form = $this->createForm(CustomerType::class, $customer, [
            'method' => 'PUT',
            'firstName' => $customer->getFirstName(),
            'lastName' => $customer->getLastName(),
            'email' => $customer->getEmail(),
            'phoneNumber' => $customer->getPhoneNumber(),
            'dob' => $customer->getDob(),
            'gender' => $customer->getGender(),
            'hobbies' =>  empty($customer->getHobbies()) ?: explode(',', $customer->getHobbies()),
            'address' => $customer->getAddress(),
            'image' => $customer->getImage(),
            'img_required' => false,
        ]);

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
            empty($form->getProduct()) ? true : $customer->setProduct($form->getProduct());

            /** @var UploadedFile $imageFile */
            $imageFile = $form->getImage();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $image_updaload_rsp = $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                    if (!empty($data['image'])) {
                        $filesystem = new Filesystem();
                        $path = $this->getParameter('images_directory') . '/' . $data['image'];
                        $filesystem->remove($path);
                    }
                } catch (FileException $e) {
                    // Exeption Occured
                    // $error = new FormError("Somthing went wrong while uploading image ");
                    // $form->get('image')->addError($error);
                    // return $this->render('customer/add.html.twig', [
                    //     'form' => $form->createView(),
                    // ]);
                }
                empty($newFilename) ? true : $customer->setImage($newFilename);
            } else {
                // empty($data['image']) ? true : $customer->setImage($data['image']);
                empty($data['image']) ? true : $customer->setImage($data['image']);
            }
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
