<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Contact notifications
    |--------------------------------------------------------------------------
    |
    | When set, every message sent through the contact form is also e-mailed
    | to this address using the configured mailer. Messages are always stored
    | in the admin inbox either way.
    |
    */

    'contact_notify_email' => env('CONTACT_NOTIFY_EMAIL', env('ADMIN_EMAIL')),

];
