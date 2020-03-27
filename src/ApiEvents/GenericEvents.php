<?php

namespace App\ApiEvents;

/**
 * Class GenericEvents
 * 
 * this class contains all general events
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
final class GenericEvents
{
    /**
     * The FILE_UPLOAD event occurs when a file upload is called for an uploadable object.
     *
     * @Event("App\Event\FileUploadEvent")
     */
    const FILE_UPLOAD = 'generic.file.upload';
}
