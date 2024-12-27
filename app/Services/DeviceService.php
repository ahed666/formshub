<?php

namespace App\Services;

use App\Models\Form;
use App\Models\Device;
use App\Events\FormUpdated;
use App\Events\DeviceEdited;
use App\Events\DeviceAdded;
use App\Events\DeviceDeleted;
use App\Events\RefreshDevice;
use App\Events\RestartDevice;
use App\Models\DeviceCode;
use Illuminate\Support\Facades\Auth;

class DeviceService
{

    public function updateDevicesByForm($formId){

        $deviceCodes=Device::where('form_id',$formId)->get()->pluck('code');
        foreach ($deviceCodes as $code) {
            event(new FormUpdated($code));
        }
    }

    public function refreshDevice($code){
        event(new RefreshDevice($code));
    }

    public function restartDevice($code){
        event(new RestartDevice($code));
    }


    public function generateDeviceCodeWithPin($data)
    {
        $pin = $this->generateUniquePin();
        $code = $this->generateUniqueCode();
       

        DeviceCode::create(['code' => $code, 'pin' => $pin,'device_info'=> json_encode($data['deviceInfo']),'app_version'=>$data['appVersion'],'screen_w'=>$data['screenWidth'],'screen_h'=>$data['screenHeight']]);

        return ['pin' => $pin, 'code' => $code];
    }

    private function generateUniquePin()
    {
        do {
            $pin = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
        } while (DeviceCode::where('pin', $pin)->exists());

        return $pin;
    }

    private function generateUniqueCode()
    {
        do {
            $code = str_pad(mt_rand(0, 9999999999999999), 16, '0', STR_PAD_LEFT);
        } while (Device::where('code', $code)->exists() || DeviceCode::where('code', $code)->exists());

        return $code;
    }


     // Delete device
     public function deleteDevice($deviceId)
     {
         $device = Device::find($deviceId);
         if (!$device) {
             throw new \Exception("Device not found");
         }
 
         event(new DeviceDeleted($device->code));
         $device->delete();
         
         return $device->code;
     }



     // Add a new device
    public function addDevice($request)
    {
        $deviceCode = DeviceCode::where('pin', $request->pin)->first();
        if (!$deviceCode) {
            throw new \Exception("Pin not available");
        }

        $device = new Device();
        $device->name = $request->name;
        $device->user_id = Auth::user()->id;
        $device->remark = $request->remark;
        $device->form_id = null;
        $device->pin = $request->pin;
        $device->code = $deviceCode->code;
        $device->tag = $request->tagColor;
        $device->rotation = $request->selectedRotation;
        $device->device_info=$deviceCode->device_info;
        $device->app_version=$deviceCode->app_version;
        $device->screen_w=$deviceCode->screen_w;
        $device->screen_h=$deviceCode->screen_h;
        
        $device->save();

        // Delete the device code after use
        $deviceCode->delete();
        event(new DeviceAdded($device->code, $device));

        return $device;
    }

    public function editDevice($deviceData)
    {
        $device = Device::find($deviceData['id']);
        if (!$device) {
            throw new \Exception("Device not found");
        }

        $device->name = $deviceData['name'];
        $device->remark = $deviceData['remark'];
        $device->tag = $deviceData['tag'];
        $device->rotation = $deviceData['rotation'];
        $device->form_id = $deviceData['form_id'];
        $device->save();

        event(new DeviceEdited($device->code, $device));

        return $device;
    }
    // Check if device exists by pin
    public function checkDevice($pin)
    {
        return DeviceCode::where('pin', $pin)->exists();
    }

    // Get form for device
    public function getDeviceForm($deviceId)
    {
        $device = Device::find($deviceId);
        if (!$device) {
            throw new \Exception("Device not found");
        }

        if ($device->form_id) {
            $form = Form::with([
                'translations' => function ($query) {
                    $query->where('enable', true);
                },
                'questions' => function ($query) {
                    $query->orderBy('order');
                },
                'questions.translations',
                'questions.type',
                'questions.answers.translations'
            ])->find($device->form_id);

            if (!$form) {
                throw new \Exception("Form not found");
            }

            return $form;
        } else {
            return null; // No form assigned (standby status)
        }
    }

    public function getDevicesForUser(){
      
        return Device::where('user_id', Auth::user()->id)->withCount('responses')->get();
 
    }
    
}
