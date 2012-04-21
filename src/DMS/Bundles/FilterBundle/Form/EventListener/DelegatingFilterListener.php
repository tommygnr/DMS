<?php
namespace DMS\Bundles\FilterBundle\Form\EventListener;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Event\FilterDataEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use DMS\Bundles\FilterBundle\Service\Filter;

/**
 * Delegating Filter Listener
 *
 * This subscriber listens to form events to automatically run filtering
 * on the attached entity, like Validation is done.
 */
class DelegatingFilterListener implements EventSubscriberInterface
{
    /**
     * @var \DMS\Bundles\FilterBundle\Service\Filter
     */
    protected $filterService;

    /**
     * @param \DMS\Bundles\FilterBundle\Service\Filter $filterService
     */
    public function __construct(Filter $filterService)
    {
        $this->filterService = $filterService;
    }

    /**
     * {@inheritdoc}
     */
    static public function getSubscribedEvents()
    {
        return array(
            FormEvents::POST_BIND        => "onPostBind",
        );
    }

    /**
     * Listens to the Post Bind event and triggers filtering if adequate.
     *
     * @param FilterDataEvent $event
     */
    public function onPostBind($event)
    {

        $clientData = $event->getForm()->getClientData();

        if (is_object($clientData)) {
            $this->filterService->filterEntity($clientData);
        }

    }
}