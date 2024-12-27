




var deviceCode;
var pin;
var ConnectStatus;
var refreshChannel;
var deviceDeletedChannel;
var deviceEditedChannel;
var deviceAddedChannel;
var device;
var playlists;
var mainDiv=document.getElementById('main');
//  when initial page
document.addEventListener("DOMContentLoaded", function() {
    // ConnectStatus=handleConnectionChange();

// check about device code
CheckDeviceCode();


});

// initial refresh channel
function InitialChannels(code) {

    // refresh playlist
    refreshChannel = pusher.subscribe('refresh-playlist-' + code);
    refreshChannel.bind('App\\Events\\PlaylistUpdated', function() {

        device=JSON.parse(getCookie("device"));
        loadData(device.pin,device);



    });

    // device added
    deviceAddedChannel=pusher.subscribe('device-added-' + code);
    deviceAddedChannel.bind('App\\Events\\DeviceAdded', function(event) {


        setCookie('device', JSON.stringify(event.device), 365);
        loadData(event.device.pin,event.device);




    });

    // device edited
    deviceEditedChannel=pusher.subscribe('device-edited-' + code);
    deviceEditedChannel.bind('App\\Events\\DeviceEdited', function(event) {


        setCookie('device', JSON.stringify(event.device), 365);


        loadData(event.device.pin,event.device);



    });


    // device deleted
    deviceDeletedChannel=pusher.subscribe('device-deleted-' + code);
    deviceDeletedChannel.bind('App\\Events\\DeviceDeleted', function() {


        deleteAllCookies();
        localStorage.clear();

        // delete storage,cache,cookies and reload page on android
        try {
            // Android.clearCache();
            // Android.clearLocalStorage();
            // Android.ClearCookies();
            // Android.PageReload();
        } catch (error) {

        }
        window.location.reload();




    });

}


// function  updateInfo(device){
//     console.log('update info:',device,device.tag);
//     document.getElementById('body').style.backgroundColor=device.tag;
//     document.getElementById('main').style.transform=`rotate(${device.rotation}deg)` ;
// }


// check if there is device code
function CheckDeviceCode(){
     deviceCode = getCookie("device_code");
     pin=getCookie("device_pin");
     device =getCookie("device");


     if(device)device=JSON.parse(getCookie("device"));


    if (deviceCode) {
        // check if there is device code then initial channels
        InitialChannels(deviceCode);
        // Device code exists, load data using the code or show pin
        device?loadData(pin,device):loadData(pin);

    } else {
        // Device code doesn't exist, make a request to the server to generate a new code
        generateNewCode();
    }
}

// to do when start work on cach playlist
// status online or offline
function handleConnectionChange(event) {

    var status = navigator.onLine ;

    return status;
}

// show device code
function showDeviceCode(pin) {

    // document.getElementById("device-code").innerHTML = deviceCode;
    const pinContainer = document.createElement('div');
    pinContainer.classList.add('flex','justify-between','font-bold', 'items-center','min-h-[100vh]');
    for (let index = 0; index < pin.length; index++) {
        const digitDiv = document.createElement('div');
        digitDiv.innerText = pin[index];
        digitDiv.classList.add('p-2','text-black','font-bold','text-2xl');
        pinContainer.appendChild(digitDiv);
    }
    mainDiv.appendChild(pinContainer);

}



// Check connection status on page load
// window.addEventListener('load', handleConnectionChange);

// // Listen for online and offline events

window.addEventListener("online",  CheckDeviceCode);

// window.addEventListener("offline", handleConnectionChange);



// set device config
function setDeviceConfig(device){
    // set device rotate
    mainDiv.style.transform=`rotate(${device.rotation}deg)` ;

}

// load data or show device code if there is device (device=null)
async function loadData(pin, device = null) {

    // if there is device then load it playlists else show device code
    if (device)
    {

       let connection=handleConnectionChange(); //test if there  is connection or no
       if(connection)
        // if there is connection
        {

            setDeviceConfig(device);  //set device config like rotate .....
            try {



                player.innerHTML='fetching data..';
                // fetch  device playlists
                const data = await getPlaylistsDevice(device.id);
                 console.log(device.id,data);
                // after fetch data from server then store it and start playlist
                if (data && data.playlists)
                {
                    // if there is playlists first store it and then show it
                    await storePlaylists(data.playlists);//store playlists by handle-storage.js
                    player.innerHTML='data fetched ..';
                    startPlaylistsOnScreen(); //show playlists  by handle-paly.js
                }
            }
            catch (error) {

                console.error('Error in loadData:', error);
            }
        }
        // if not connection
        else{

            startPlaylistsOnScreen();
        }
    }
    // if there is not device then show code
    // Code to load data using the device code
    else if(pin)
   {
       showDeviceCode(pin);
   }
}


// send generate code to server and response the code
function generateNewCode() {

    // get device info
    
    //    let deivceInfo=Android.getDeviceInfo();
     


    // Code to send a request to the server for generate a new code

    // call the api
    fetch('/generate-code', {
        method: 'POST',

        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.Laravel.csrfToken
        }
    })

    // get response
    .then(response => response.json())
    .then(data => {

        showDeviceCode(data.pin);//show pin on screen

        setCookie('device_code', data.code, 365);  // Expires in 1 year
        setCookie('device_pin', data.pin, 365);    // Expires in 1 year

        InitialChannels(data.code);



    })
    .catch(error => console.error('Error:', error));
}
