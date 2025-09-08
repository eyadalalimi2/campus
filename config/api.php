<?php
return [
    'pagination' => [
        'default' => 20,
        'max'     => 50,
    ],

    'throttle' => [
        // معدلات افتراضية لدوال RateLimiter (يمكن ربطها في RouteServiceProvider)
        'guests' => 30,   // طلب/دقيقة/IP
        'users'  => 120,  // طلب/دقيقة/token
    ],

    'feed' => [
        'window_assets'   => 30, // يوم
        'window_contents' => 30, // يوم
    ],

    'idempotency' => [
        'ttl_hours' => 24,
    ],

    // مفاتيح زمنية مركزية للرموز الحساسة
    'security' => [
        'email_verification_ttl_minutes' => 15,
        'password_reset_ttl_hours'       => 1,
    ],
];
