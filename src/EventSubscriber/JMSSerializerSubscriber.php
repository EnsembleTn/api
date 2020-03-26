<?php

namespace App\EventSubscriber;

use App\Entity\Patient;
use App\Manager\FileManager;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\Metadata\StaticPropertyMetadata;

/**
 * Class JMSSerializerSubscriber
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class JMSSerializerSubscriber implements EventSubscriberInterface
{
    /**
     * @var FileManager
     */
    private $fileManager;


    /**
     * JMSSerializeSubscriber constructor.
     * @param FileManager $fileManager
     */
    public function __construct(
        FileManager $fileManager
    )
    {
        $this->fileManager = $fileManager;
    }

    /**
     * JMS Serializer Subscribed Events
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            [
                'event' => 'serializer.post_serialize',
                'method' => 'onPostSerializeUser',
                'class' => Patient::class,
            ],
        ];
    }

    /**
     * Altering patient after serialization
     *
     * @param ObjectEvent $event
     */
    public function onPostSerializeUser(ObjectEvent $event): void
    {
        $patient = $event->getObject();

        if (!$patient instanceof Patient) {
            return;
        }

        $audio = $this->fileManager->getFile($patient);

        if ($audio) {
            // attaching user profile & cover pictures
            $event->getVisitor()->visitProperty(new StaticPropertyMetadata('', 'audio', null), $audio->getBase64EncodedString());
        }
    }
}
