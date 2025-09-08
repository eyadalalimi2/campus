<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    // كشف بعض الرؤوس للعميل (مفيد للتعامل مع المعدّل والترقيم)
    'exposed_headers' => [
        'Authorization',
        'X-RateLimit-Limit',
        'X-RateLimit-Remaining',
        'Retry-After',
        'Idempotency-Key',
        'X-Idempotency-Cache',
        'X-Request-Id',
        'X-Correlation-Id',
        'Content-Disposition'
    ],


    // تخزين نتيجة الـ preflight في المتصفح (لا يؤثر على تطبيق أندرويد)
    'max_age' => 3600,

    // لواجهة Android عبر Bearer لا نحتاج كوكيز stateful
    'supports_credentials' => false,

];
