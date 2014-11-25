<?php

return array(

    'IOS'     => array(
        'environment' => $_ENV['APNS_ENV'],
        'certificate' => $_ENV['APNS_CERTIFICATE'],
        'passPhrase'  => $_ENV['APNS_PASSPHRASE'],
        'service'     =>'apns'
    ),
    'appNameAndroid' => array(
        'environment' =>'production',
        'apiKey'      =>'yourAPIKey',
        'service'     =>'gcm'
    )

);
