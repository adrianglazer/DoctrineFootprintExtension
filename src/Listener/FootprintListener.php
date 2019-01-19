<?php

namespace Glazer\DoctrineFootprintExtension\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class FootprintListener implements EventSubscriber
{
    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function getSubscribedEvents()
    {
        return [Events::preRemove, Events::prePersist, Events::preUpdate];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (method_exists($entity, 'setCreatedAt')) {
            $entity->setCreatedAt(new \DateTime('now', new \DateTimeZone('UTC')));
        }

        if (method_exists($entity, 'setCreatedBy') && $this->tokenStorage->getToken()) {
            $entity->setCreatedBy($this->tokenStorage->getToken()->getUsername());
        }
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        if (method_exists($entity, 'setUpdatedAt')) {
            $entity->setUpdatedAt(new \DateTime('now', new \DateTimeZone('UTC')));
        }

        if (method_exists($entity, 'setUpdatedBy') && $this->tokenStorage->getToken()) {
            $entity->setUpdatedBy($this->tokenStorage->getToken()->getUsername());
        }
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (method_exists($entity, 'setDeletedAt')) {
            $entity->setDeletedAt(new \DateTime('now', new \DateTimeZone('UTC')));
        }

        if (method_exists($entity, 'setDeletedBy') && $this->tokenStorage->getToken()) {
            $entity->setDeletedBy($this->tokenStorage->getToken()->getUsername());
        }

        if (method_exists($entity, 'setDeletedAt') || method_exists($entity, 'setDeletedBy')) {
            $args->getEntityManager()->getUnitOfWork()->detach($entity);
            $args->getEntityManager()->merge($entity);
        }
    }
}