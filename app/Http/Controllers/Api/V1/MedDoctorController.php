<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorResource;
use App\Models\{MedDoctor, MedSubject};

class MedDoctorController extends Controller
{
    public function index()
    {
        $doctors = MedDoctor::where('status', 'published')
            ->orderBy('order_index')
            ->get();

        return DoctorResource::collection($doctors);
    }

    public function bySubject(MedSubject $subject)
    {
        $doctors = $subject->doctors()
            ->where('med_doctors.status', 'published')
            ->orderBy('med_doctors.order_index')
            ->get();

        return DoctorResource::collection($doctors);
    }
}
