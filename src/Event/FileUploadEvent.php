<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class FileUploadEvent
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class FileUploadEvent extends Event
{
    /**
     * @var mixed
     */
    protected $object;

    /**
     * @var string
     */
    protected $base64EncodedFile;

    /**
     * @var bool
     */
    private $uploadToServer;


    /**
     * FileUploadEvent constructor.
     *
     * @param mixed $object
     * @param string $base64EncodedFile
     * @param bool $uploadToServer
     */
    public function __construct($object, ?string $base64EncodedFile, bool $uploadToServer)
    {
        $this->object = $object;
        $this->base64EncodedFile = $base64EncodedFile;
        $this->uploadToServer = $uploadToServer;
    }

    /**
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }

    public function getBase64EncodedFile(): ?string
    {
        return $this->base64EncodedFile;
    }

    public function isUploadableToServer(): bool
    {
        return $this->uploadToServer;
    }
}
