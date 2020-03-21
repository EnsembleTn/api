<?php

namespace App\Service;

/**
 * Created By IJ
 * @author : Ala Daly <ala.daly@dotit-corp.com>
 * @date : 21‏/3‏/2020, Sat
 **/

interface MailerInterface
{
    /**
     * Send email
     *
     * @param string $subject
     * @param $recipient
     * @param $body
     */
    public function sendEmail(string $subject, $recipient, $body): void;

    /**
     * Send email
     *
     * @param string $subject
     * @param $recipient
     * @param $body
     * @param $attachment
     */
    public function sendEmailWithAttachment(string $subject, $recipient, $body, $attachment):void ;

    /**
     * Get the mailer user email address
     *
     * @return string
     */
    public function getMailerUser(): string;

    public function getFrontEndServerUrl():string ;

    public function getBackEndServerUrl():string  ;
}