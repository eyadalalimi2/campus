<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubjectResource;
use App\Models\{MedSubject, MedDevice};

class MedSubjectController extends Controller
{
    public function index()
    {
        $scope = request('scope'); // basic | clinical | both

        $subjects = MedSubject::query()
            ->when($scope, fn($q) => $q->where('scope', $scope))
            ->where('status', 'published')
            ->orderBy('order_index')
            ->get();

        return SubjectResource::collection($subjects);
    }

    public function byDevice(MedDevice $device)
    {
        $subjects = $device->subjects()
            ->where('status', 'published')
            ->orderBy('order_index')
            ->get();

        return SubjectResource::collection($subjects);
    }
}
