<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 * @UniqueEntity( fields={"phoneNumber"}, errorPath="phoneNumber", message="This phone number is already in use." )
 * @UniqueEntity( fields={"email"}, errorPath="email", message="This email is already in use." )
 */
class Customer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email( 
     * message = "The email is not a valid email.", 
     * ) 
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255,unique=true)
     * @Assert\NotBlank
     * @Assert\Length( 
     * min = 10, 
     * max = 11, 
     * minMessage = "Phone Number must be at least {{ limit }} digit", 
     * maxMessage = "Phone Number cannot be longer than {{ limit }} digit" 
     * )
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $dob;

    /**
     * @ORM\Column(type="string", columnDefinition="enum('Male', 'Female')")
     * @Assert\NotBlank
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $hobbies;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #* @Assert\NotBlank

    private $image;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getDob()
    {
        return $this->dob;
    }

    public function setDob(string $dob): self
    {
        $this->dob = $dob;

        return $this;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getHobbies()
    {
        return $this->hobbies;
    }

    public function setHobbies($hobbies): self
    {
        $this->hobbies = $hobbies;

        return $this;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'firstName' => $this->getFirstName(),
            'lastName' => $this->getLastName(),
            'email' => $this->getEmail(),
            'phoneNumber' => $this->getPhoneNumber(),
            'dob' => $this->getDob(),
            'gender' => $this->getGender(),
            'hobbies' => $this->getHobbies(),
            'address' => $this->getAddress(),
            'image' => $this->getImage(),

        ];
    }
}
