<?php

return array(

    'IOS'     => array(
        'environment' =>'development',
        'certificate' => storage_path().'/ck.pem',
        'passPhrase'  =>'test',
        'service'     =>'apns'
    ),
    'appNameAndroid' => array(
        'environment' =>'production',
        'apiKey'      =>'yourAPIKey',
        'service'     =>'gcm'
    )

);