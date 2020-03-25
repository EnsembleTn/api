<?php

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class SMS
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class TTSMSing implements SMSInterface
{
    CONST TN_PHONE_NUMBER_REGEX = '/^([259][0-8]{7}|(3[012]|4[01])[0-9]{6})$/';
    CONST SMS_LENGTH = 160;
    CONST TN_PHONE_NUMBER_PREFIX = '216';

    private $gateway;
    private $username;
    private $password;
    private $appID;
    private $soa;
    private $flags;

    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    /**
     * Base64Uploader constructor.
     *
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    /**
     * @inheritDoc
     */
    function send(int $destinationAddress, string $content): string
    {
        if (!$this->validate($destinationAddress, $content))
            return;

        $this->initParams();

        $client = new Client();

        $request = new Request('GET', $this->gateway, [
            'query' => [
                'UserName' => $this->username,
                'Password' => $this->password,
                'SenderAppId' => $this->appID,
                'DA' => sprintf("%s%s", self::TN_PHONE_NUMBER_PREFIX, $destinationAddress),
                'SOA' => $this->soa,
                'Content' => $content,
                'Flags' => $this->flags,
            ]
        ]);

        $promise = $client->sendAsync($request)->then(function ($response) {
            echo 'Completed! ' . $response->getBody();
        });

        $promise->wait();
    }

    private function initParams(): void
    {
        $this->gateway = $this->parameterBag->get('tt_sms_gateway');
        $this->username = $this->parameterBag->get('tt_sms_username');
        $this->password = $this->parameterBag->get('tt_sms_password');
        $this->appID = $this->parameterBag->get('tt_sms_sender_app_id');
        $this->soa = $this->parameterBag->get('tt_sms_soa');
        $this->flags = $this->parameterBag->get('tt_sms_flags');
    }

    private function validate(int $destinationAddress, string $content): bool
    {
        return (!preg_match(self::TN_PHONE_NUMBER_REGEX, $destinationAddress) or strlen($content) > self::SMS_LENGTH);
    }
}
