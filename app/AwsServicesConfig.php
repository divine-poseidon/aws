<?php


namespace app;


use Aws\Credentials\Credentials;

class AwsServicesConfig
{
    public static function getServicesParams(string $region = null, Credentials $credentials = null, string $version = null): array
    {
        return [
            'version' => $version ?? 'latest',
            'region' => $region ?? 'us-east-1',
            'credentials' => $credentials ?? new Credentials(getenv('AWS_KEY'), getenv('AWS_SECRET'))
        ];
    }
}