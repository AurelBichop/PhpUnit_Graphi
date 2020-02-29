<?php
namespace App\Tests\EventSubscriber;

use App\EventSubscriber\ExceptionSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelInterface;


class ExceptionSubscriberTest extends TestCase {

    public function testEventSubcription (){
        $this->assertArrayHasKey(ExceptionEvent::class, ExceptionSubscriber::getSubscribedEvents());
    }

    public function testOnExceptionSendEmail(){

        $mailer = $this->getMockBuilder(\Swift_Mailer::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mailer->expects($this->once())->method('send');

        $this->dispatch($mailer);

    }

    public function testOnExceptionSendMailToTheAdmin(){
        $mailer = $this->getMockBuilder(\Swift_Mailer::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mailer->expects($this->once())
            ->method('send')
            ->with($this->callback(function (\Swift_Message $message){
                return
                    array_key_exists('from@domain.fr', $message->getFrom()) &&
                    array_key_exists('to@domain.fr', $message->getTo());
            }));

        $this->dispatch($mailer);
    }

    public function testOnExceptionSendMailWithTheTrace(){
        $mailer = $this->getMockBuilder(\Swift_Mailer::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mailer->expects($this->once())
            ->method('send')
            ->with($this->callback(function (\Swift_Message $message){

                if (strpos($message->getBody(), 'ExceptionSubscriberTest') && strpos($message->getBody(), 'Hello World !')){
                        $retour = true;
                }else{
                        $retour =false;
                }

                return $retour;
            }));

        $this->dispatch($mailer);
    }

    public function dispatch($mailer){
        $subscriber = new ExceptionSubscriber($mailer, 'from@domain.fr', 'to@domain.fr');
        $kernel = $this->getMockBuilder(KernelInterface::class)->getMock();
        $event = new ExceptionEvent($kernel, new Request(), 1 ,new \Exception('Hello World !'));

        //*********************************************
        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber($subscriber);
        $dispatcher->dispatch($event);
    }


}