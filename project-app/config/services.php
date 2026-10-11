<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'saml2' => [
        'enabled' => env('SAML2_ENABLED', false),
        'simulator_enabled' => env('SAML_SIMULATOR', false),
        'email_attribute' => env('SAML2_EMAIL_ATTRIBUTE', 'uCorreo'),
        'name_attribute' => env('SAML2_NAME_ATTRIBUTE', 'uNombre'),
        'simulator_email' => env('SAML_SIMULATOR_EMAIL', 'alumno@example.test'),
        'simulator_name' => env('SAML_SIMULATOR_NAME', 'Alumno de pruebas'),
        'simulator_firstname' => env('SAML_SIMULATOR_FIRSTNAME', 'Nombre de prueba'),
        'simulator_lastname' => env('SAML_SIMULATOR_LASTNAME', 'Apellido de prueba'),
        'sp_entity_id' => env('SAML2_SP_ENTITY_ID'),
        'idp_entity_id' => env('SAML2_IDP_ENTITY_ID'),
        'idp_sso_url' => env('SAML2_IDP_SSO_URL'),
        'idp_x509_cert' => env('SAML2_IDP_X509_CERT'),
    ],

];
