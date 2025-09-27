<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeviceResource;
use App\Models\MedDevice;

class MedDeviceController extends Controller
{
    public function index()
    {
        $devices = MedDevice::where('status', 'published')
            ->orderBy('order_index')
            ->get();

        return DeviceResource::collection($devices);
    }
}
