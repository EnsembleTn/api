<?php

namespace App\Service;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class SMS
 *
 * @author Daly Ghaith <daly.ghaith@gmail.com>
 */
class TTSMSing implements SMSInterface
{
    CONST TN_PHONE_NUMBER_REGEX = '/^([259][0-9]{7}|(3[012]|4[01])[0-9]{6})$/';
    CONST SMS_LENGTH = 620;
    CONST TN_PHONE_NUMBER_PREFIX = '216';
    CONST TT_SMS_GATEWAY_SUCCESS_CODE = '0';

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
    function send(int $destinationAddress, string $content): void
    {
        if (!$this->validate($destinationAddress, $content))
            return;

        $this->initParams();

        $uri = new Uri($this->gateway);
        $queryParams = [
            'UserName' => $this->username,
            'Password' => $this->password,
            'SenderAppId' => $this->appID,
            'DA' => sprintf("%s%s", self::TN_PHONE_NUMBER_PREFIX, $destinationAddress),
            'SOA' => $this->soa,
            'Content' => utf8_decode($content),
            'Flags' => $this->flags,
        ];

        $client = new Client();
        $request = new Request('GET', $uri->withQuery(http_build_query($queryParams)));

        // Response coming from gateway is in text/html format, will be using dom crawler to retrieve status code
        $promise = $client->sendAsync($request)->then(function ($response) {
            $crawler = new Crawler($response->getBody()->getContents());

            $body = $crawler->filter('body')->text();
            $statusCode = str_replace("Status=", "", strtok($body, "\n"));

            if (self::TT_SMS_GATEWAY_SUCCESS_CODE !== $statusCode)
                throw new Exception("Error when sending sms, exited with status code = {$statusCode}");
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
        return (preg_match(self::TN_PHONE_NUMBER_REGEX, $destinationAddress) and strlen($content) <= self::SMS_LENGTH);
    }
}
