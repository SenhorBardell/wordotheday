<?php 

return array( 

    /*
    |--------------------------------------------------------------------------
    | oAuth Config
    |--------------------------------------------------------------------------
    */

    /**
     * Storage
     */
    'storage' => 'Session', 

    /**
     * Consumers
     */
    'consumers' => array(

        /**
         * Vkontakte
         */
        'Vkontakte' => array(
            'client_id'     => '4381561',
            'client_secret' => 'KFHDe8VMAFKkPltVqYa3',
            'scope'         => array('friends'),
        ),      

    )

);