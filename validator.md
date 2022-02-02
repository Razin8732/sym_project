
App\Entity\Customer
-------------------

+-------------+--------------------------------------------------+-------------------+----------------------------------------------------------------------------------+
| Property    | Name                                             | Groups            | Options                                                                          |
+-------------+--------------------------------------------------+-------------------+----------------------------------------------------------------------------------+
| firstName   | Symfony\Component\Validator\Constraints\NotBlank | Default, Customer | [                                                                                |
|             |                                                  |                   |   "allowNull" => false,                                                          |
|             |                                                  |                   |   "message" => "This value should not be blank.",                                |
|             |                                                  |                   |   "normalizer" => null,                                                          |
|             |                                                  |                   |   "payload" => null                                                              |
|             |                                                  |                   | ]                                                                                |
| lastName    | Symfony\Component\Validator\Constraints\NotBlank | Default, Customer | [                                                                                |
|             |                                                  |                   |   "allowNull" => false,                                                          |
|             |                                                  |                   |   "message" => "This value should not be blank.",                                |
|             |                                                  |                   |   "normalizer" => null,                                                          |
|             |                                                  |                   |   "payload" => null                                                              |
|             |                                                  |                   | ]                                                                                |
| email       | Symfony\Component\Validator\Constraints\Email    | Default, Customer | [                                                                                |
|             |                                                  |                   |   "message" => "The email '{{ value }}' is not a valid email.",                  |
|             |                                                  |                   |   "mode" => null,                                                                |
|             |                                                  |                   |   "normalizer" => null,                                                          |
|             |                                                  |                   |   "payload" => null                                                              |
|             |                                                  |                   | ]                                                                                |
| phoneNumber | Symfony\Component\Validator\Constraints\NotBlank | Default, Customer | [                                                                                |
|             |                                                  |                   |   "allowNull" => false,                                                          |
|             |                                                  |                   |   "message" => "This value should not be blank.",                                |
|             |                                                  |                   |   "normalizer" => null,                                                          |
|             |                                                  |                   |   "payload" => null                                                              |
|             |                                                  |                   | ]                                                                                |
| phoneNumber | Symfony\Component\Validator\Constraints\Length   | Default, Customer | [                                                                                |
|             |                                                  |                   |   "allowEmptyString" => false,                                                   |
|             |                                                  |                   |   "charset" => "UTF-8",                                                          |
|             |                                                  |                   |   "charsetMessage" => "This value does not match the expected {{ charset }} char |
|             |                                                  |                   | set.",                                                                           |
|             |                                                  |                   |   "exactMessage" => "This value should have exactly {{ limit }} character.|This  |
|             |                                                  |                   | value should have exactly {{ limit }} characters.",                              |
|             |                                                  |                   |   "max" => 11,                                                                   |
|             |                                                  |                   |   "maxMessage" => "Phone Number cannot be longer than {{ limit }} digit",        |
|             |                                                  |                   |   "min" => 10,                                                                   |
|             |                                                  |                   |   "minMessage" => "Phone Number must be at least {{ limit }} digit",             |
|             |                                                  |                   |   "normalizer" => null,                                                          |
|             |                                                  |                   |   "payload" => null                                                              |
|             |                                                  |                   | ]                                                                                |
+-------------+--------------------------------------------------+-------------------+----------------------------------------------------------------------------------+
