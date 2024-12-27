<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlaylistDevice;
use App\Models\Playlist;
use App\Models\MediaPlaylist;
use App\Models\Schedule;
class DevicePlayerController extends Controller
{
    //

    public function index(){

        return view('device_player');
    }

    public function getContent($deviceId){
        $playlistsDeviceIds = PlaylistDevice::where('device_id',$deviceId)->where('enable',true)->pluck('playlist_id')->toArray();
         $playlists=Playlist::whereIn('id',$playlistsDeviceIds)->with(['media', 'schedule'])->get();

        //  $schedules=Schedule::whereIn('playlist_id',$playlistsDeviceIds)->get();
        return response()->json([
            'playlists' => $playlists
        ]);
        
    }
}
