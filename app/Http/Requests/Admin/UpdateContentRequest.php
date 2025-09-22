<?php

namespace App\Http\Requests\Admin;

class UpdateContentRequest extends StoreContentRequest
{
    // ترث نفس القواعد والتحققات.
    // إن أردت تغيير قيود الملف في التحديث (السماح بعدم وجوده دائمًا)،
    // فالقواعد الحالية بالفعل تتعامل مع ذلك عبر required_if:type,file.
}
