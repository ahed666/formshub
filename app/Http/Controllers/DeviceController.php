<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\DeviceService;
use Illuminate\Http\Request;
use App\Policies\SubscriptionFeaturePolicy;
use App\Models\Device;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class DeviceController extends Controller
{
    protected $deviceService;
    protected $featurePolicy;

    public function __construct(SubscriptionFeaturePolicy $featurePolicy,DeviceService $deviceService)
    {
        $this->featurePolicy=$featurePolicy;
        $this->deviceService = $deviceService;
    }

    public function index()
    {
        $devices = $this->deviceService->getDevicesForUser();
        $maxPlaylistsNum = config('app.MAX_DEVICE_PLAYLISTS');
        return view('devices', compact('devices', 'maxPlaylistsNum'));
    }

    public function refreshDevice($code){
        try {
            $this->deviceService->refreshDevice($code);
            return response()->json(['message' => __('app.devices.refresh_device_success')]);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th]);
        }

       
    }
    public function restartDevice($code){
       
        try {
            $this->deviceService->restartDevice($code);

            return response()->json(['message' => __('app.devices.restart_device_success')]);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th]);
        }

     }

    public function generateDeviceCodeWithPin(Request $request)
    {
      

       
        $data = $this->deviceService->generateDeviceCodeWithPin($request->all());
        return response()->json($data);
    }

    public function deleteDevice($deviceId)
    {
        try {
            $code = $this->deviceService->deleteDevice($deviceId);
            return response()->json(['message' => $code]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function checkDevice($pin)
    {
        $exists = $this->deviceService->checkDevice($pin);
        return response()->json(['message' => $exists]);
    }

    public function addDevice(Request $request)
    {          
        $user=User::findOrFail(Auth::user()->id);
        $allowChecking=($this->featurePolicy->canAdd('device',$user))->getData();
        if($allowChecking->result){
        try {
            $device = $this->deviceService->addDevice($request);
            return response()->json(['can'=>$allowChecking->result,'message' => __('app.devices.successadddevice'), 'device' => $device]);
        } catch (\Exception $e) {
            return response()->json(['can'=>false,'message' => $e->getMessage()]);
        }}
        else
        return response()->json(['can'=>$allowChecking->result,'message' => $allowChecking->resultmessage]);


    }

    public function editDevice(Request $request)
    {
        try {
            $device = $this->deviceService->editDevice($request->device);
            return response()->json(['message' => 'Device edited successfully!', 'device' => $device],200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()],500);
        }
    }

    public function getDeviceForm($deviceId)
    {
        $device=Device::findOrFail($deviceId);
        
        $user=User::findOrFail($device->user_id);
        
        try {
            // check if user can add response with current subscription
            $allowChecking=($this->featurePolicy->canAdd('response',$user))->getData();
           
           
            if($allowChecking->result==false){
                
                return response()->json(['status' => 'subscriptionInactive', 'message' => $allowChecking->resultmessage], 200);

            }
            // if can then check also if there is form
            // if there is not form on this device then the status of it standby
            else
            {
            $form = $this->deviceService->getDeviceForm($deviceId);

            if ($form) {
                return response()->json(['form' => $form], 200);
            } else {
                
                return response()->json(['status' => 'standby', 'message' => 'Device is in standby status. No form assigned.'], 200);
            }}
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
