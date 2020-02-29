<?php


namespace App\Tests\Entity;


use App\DataFixtures\InvitationCodeFixtures;
use App\Entity\InvitationCode;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use PHPUnit\Framework\Constraint\Constraint;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolation;

class InvitationCodeTest extends KernelTestCase
{
    use FixturesTrait;

    public function getEntity (): InvitationCode{
        return (new InvitationCode())
            ->setCode('12345')
            ->setDescription('Description de test')
            ->setExpireAt(new \DateTime());
    }

    //a mettre
    public function assertHasErrors(InvitationCode $code, int $number = 0){
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($code);
        $messages = [];
        /** @var ConstraintViolation $errors */
        foreach ($errors as $error){
            $messages[] = $error->getPropertyPath() . '==>' . $error->getMessage();
        }

        $this->assertCount($number, $errors, implode(',',$messages));
    }

    public function testValidEntity(){
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testInvalidCodeEntity(){
        $this->assertHasErrors($this->getEntity()->setCode('12a34'),1);
        $this->assertHasErrors($this->getEntity()->setCode('1234'),1);
    }

    public function testInvalidBlanckCodeEntity(){
        $this->assertHasErrors($this->getEntity()->setCode(''),1);
    }

    public function testInvalidBlanckDescriptionEntity(){
        $this->assertHasErrors($this->getEntity()->setDescription(''),1);
    }

    public function testInvalidUsedCode(){
        $this->loadFixtureFiles([dirname(__DIR__).'/fixtures/invitation_codes.yaml']);
        $this->assertHasErrors($this->getEntity()->setCode('54321'),1);
    }
}