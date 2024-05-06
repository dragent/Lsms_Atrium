<?php

namespace App\Tests\Form\Staff\Object;

use App\Entity\Objects;
use App\Form\AddObjectType;
use Symfony\Component\Form\Test\TypeTestCase;

class AddTypeTest extends TypeTestCase
{

    /**
     * Teste si les données sont correctes apres la validation du formulaire d'ajout
     */
    public function testSubmitValidData(): void
    {
        $formData = [
            'name' => 'test',
            'quantity' => 50,
            'quantityTrigger' => 50,
        ];

        $model = new Objects();
        // $model will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(AddObjectType::class, $model);

        $expected = new Objects();
        $expected->setQuantity(50)
            ->setQuantityTrigger(50)
            ->setName('test');
        $form->submit($formData);

        // This check ensures there are no transformation failures
        $this->assertTrue($form->isSynchronized());

        // check that $model was modified as expected when the form was submitted
        $this->assertEquals($expected, $model);
    }
}
