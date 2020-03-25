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
        $this->initParams();

        $client = new Client();

        $request = new Request('GET', $this->gateway,[
            'query' => [
                'UserName' => $this->username,
                'Password' => $this->password,
                'SenderAppId' => $this->appID,
                'DA' => $destinationAddress,
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

    private function initParams ():void
    {
        $this->gateway = $this->parameterBag->get('tt_sms_gateway');
        $this->username = $this->parameterBag->get('tt_sms_username');
        $this->password = $this->parameterBag->get('tt_sms_password');
        $this->appID = $this->parameterBag->get('tt_sms_sender_app_id');
        $this->soa = $this->parameterBag->get('tt_sms_soa');
        $this->flags = $this->parameterBag->get('tt_sms_flags');
    }

    private function validate(): bool
    {

    }

}
