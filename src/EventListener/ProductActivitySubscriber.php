<?php

namespace App\EventListener;

use App\Entity\Product;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Psr\Log\LoggerInterface;


class ProductActivitySubscriber implements EventSubscriber
{
    /**
     * @var MailerInterface $mailer
     */
    protected $mailer;

    /**
     * @var LoggerInterface $logger
     */
    protected $logger;

    /**
     * Email that will receive Product updates
     *
     * @var string
     */
    protected $adminEmail;

    public function __construct(MailerInterface $mailer, LoggerInterface $logger, $adminEmail)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
        $this->adminEmail = $adminEmail;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::postPersist,
            Events::postRemove,
            Events::postUpdate,
        ];
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $this->logActivity('persist', $args);
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $this->logActivity('remove', $args);
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->logActivity('update', $args);
    }

    /**
     * @param string $action
     * @param LifecycleEventArgs $args
     */
    private function logActivity(string $action, LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        // if this subscriber only applies to certain entity types,
        // add some code to check the entity type as early as possible
        if (!$entity instanceof Product) {
            return;
        }

        $email = (new Email())
            ->from('admin@localhost.com')
            ->to($this->adminEmail)
            ->subject(sprintf('Product with id = %s updated', $entity->getId()))
            ->text(sprintf('Product updated, last action was "%s"', $action));
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->logger->warning('Exception sending email: ' . $e->getMessage());
        }
    }
}