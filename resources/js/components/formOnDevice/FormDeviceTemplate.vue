<template>
    <div>
 
      <div id="main" class=" max-h-[100vh] max-w-[100vw] min-h-[100vh]  rotate-0">
      <!-- Show ViewDeviceCode only if showPin is true -->
      <ViewDeviceCode v-if="showPin"  :pin="pin"></ViewDeviceCode>
      <!-- <pre>
        {{deviceData}}
      </pre> -->
      <!-- Show PlayForm only if startPlay is true and form is not null -->
      
      <PlayForm v-if="form != null && startPlay === true" :device="device" @updateCurrentStep="updateCurrentStep" :step="currentStep" :form="form" />
      <ErrorPlay v-if="error" :message="errorMessage" />
      <StandBy v-if="standbyStatus&&!error" />


      </div>
    </div>
</template>

<script>
import Pusher from 'pusher-js';
import axios from 'axios';

import ViewDeviceCode from './ViewDeviceCode.vue';
import ErrorPlay from './ErrorPlay.vue';
import PlayForm from './PlayForm.vue';
import StandBy from './StandBy.vue';
    export default {
        components:{
          ViewDeviceCode,
          PlayForm,
          ErrorPlay,
          StandBy,
    },
    props: {
    
   
      
    },
    data(){
        return {
          deviceData:null,
          pusher:null,
          showPin:false,
          standbyStatus:false,
          currentStep:0,
          startPlay:false,
            deviceCode: null,
            pin: null,
            device: null,
            form:null,
            refreshFormChannel: null,
            refreshDeviceChannel: null,
            restartDeviceChannel: null,
            deviceAddedChannel: null,
            deviceEditedChannel: null,
            deviceDeletedChannel: null,
            csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // Retrieve CSRF token
            error:false,
            errorMessage:null,
        }
    },

    mounted(){
      this.pusher = new Pusher('3581f7e6387a4abc5016', {
        cluster: 'ap2',
        encrypted: true
      });
        this.initialize();
        window.addEventListener('online', this.updateOnlineStatus);
        window.addEventListener('offline', this.updateOnlineStatus);
        
        

      },
   
    methods:{

      updateOnlineStatus() {
        this.isOnline = navigator.onLine;
        if(!this.isOnline)
        {this.currentStep=0; this.error=true;this.errorMessage="There is no internet connection";}
        else 
        { this.startFormOnScreen();}

      },

        async initialize() {
        // Check the device code when the component is mounted
        this.checkDeviceStatus();
    },

    // check on device status
    // get the device code and device info and pin from cookies 
    // if there is device(created device ) in cookies then set it to device after make parsing
    // if  there is not device then check if there is device code then initial channels for this device and load data
    // if there is not device code and not device then generate a device code
    checkDeviceStatus() {
      this.deviceCode = this.getCookie("device_code");
      this.pin = this.getCookie("device_pin");
      this.device = this.getCookie("device");

      if (this.device) {
        try {
            this.device = JSON.parse(this.device);
        } catch (error) {
            console.error("Failed to parse device cookie:", error);
            this.device = null; // or handle accordingly
        }
      }

      if (this.deviceCode) {
        this.initialChannels(this.deviceCode);
        this.device ? this.loadData(this.pin, this.device) : this.loadData(this.pin);
      } 
      else {
        this.generateNewCode();
      }
    },

    // initial channels 
    initialChannels(code) 
    {
      
      // Set up your Pusher channels here

      this.restartDeviceChannel = this.pusher.subscribe('restart-device-' + code);
      this.restartDeviceChannel.bind('App\\Events\\RestartDevice', (event) => {
        console.log('restart');
        window.location.reload();
      });

      this.refreshDeviceChannel = this.pusher.subscribe('refresh-device-' + code);
      this.refreshDeviceChannel.bind('App\\Events\\RefreshDevice', (event) => {
        this.device = JSON.parse(this.getCookie("device"));
        console.log('refresh');
        this.loadData(this.device.pin, this.device);
      });
      
      this.refreshFormChannel = this.pusher.subscribe('refresh-form-' + code);
      this.refreshFormChannel.bind('App\\Events\\FormUpdated', (event) => {
        this.device = JSON.parse(this.getCookie("device"));
        this.loadData(this.device.pin, this.device);
      });

      this.deviceAddedChannel = this.pusher.subscribe('device-added-' + code);
      this.deviceAddedChannel.bind('App\\Events\\DeviceAdded', (event) => {
        this.setCookie('device', JSON.stringify(event.device), 365);
        this.device=event.device;
        this.loadData(event.device.pin, event.device);
      });

      this.deviceEditedChannel = this.pusher.subscribe('device-edited-' + code);
      this.deviceEditedChannel.bind('App\\Events\\DeviceEdited', (event) => {
        this.setCookie('device', JSON.stringify(event.device), 365);
        this.device=event.device;
        this.loadData(event.device.pin, event.device);
      });

      this.deviceDeletedChannel = this.pusher.subscribe('device-deleted-' + code);
      this.deviceDeletedChannel.bind('App\\Events\\DeviceDeleted', () => {
        this.deleteAllCookies();
        localStorage.clear();
        window.location.reload();
      });
    },


    // load data 
    // if there is device then loading form questions
    // else then show the device code
    async loadData(pin, device = null) {
      if (device) 
      {

        let connection = this.handleConnectionChange();
        if (connection) 
        {
          this.setDeviceConfig(device);
          try 
          {
           
            const response = await this.getDeviceInfo(device.id);
            console.log(response);
           
            const data = response.data;
            await this.processingDeviceData(data,response);
            
          } catch (error) {console.error('Error in loadData:', error);}
        } 
        else {
          this.showStandByScreen();
        }
      } 
      else if (pin) {
        
        this.showDeviceCode(pin);
      }
    },

    // after getting data for device 
    async processingDeviceData(data,response){

      if (response.status==200) {
          if (data.status === 'standby') 
          {
            
            // Device is in standby status
            this.standbyStatus = true;
            this.showPin = false;
            this.form = null; // No form data
          } 
          else if (data.form) {
            
            // Form is found
            this.form = data.form;
            this.standbyStatus = false;
           
            this.startFormOnScreen();
          }
        } 
        else if(data.status='subscriptionInactive')
        {
          console.log(data.status,data.message);
          this.errorMessage = data.message || 'An error occurred';
          this.standbyStatus = false;
          this.form = null;
          this.showPin = false;
        }
        else {
         
          // Handle form not found or other errors
          this.errorMessage = data.message || 'An error occurred';
          this.standbyStatus = false;
          this.form = null;
          this.showPin = false;
        }
    },

    // show standby screen
    showStandByScreen(){

    },

    showDeviceCode(pin) {
      this.pin=pin;
        this.showPin=true;
  
    },

    // get android device info 
   async getDeviceData() {
        if (window.AndroidInterface && window.AndroidInterface.GetAndroidJSONArray) {
          try {
            // Call the Android function and parse the returned JSON
            const androidDataString = window.AndroidInterface.GetAndroidJSONArray();
            this.deviceData = JSON.parse(androidDataString);
          } catch (error) {
            console.error("Error fetching Android data:", error);
          }
        } else {
          const data = JSON.stringify({ 
            appVersion: navigator.appVersion, // Fallback if no explicit app_version
            deviceInfo: {
              userAgent: navigator.userAgent,  // Browser user agent string
              platform: navigator.platform,
            },  // Contains device/browser info
            screenWidth: window.outerWidth, // Current screen width
            screenHeight: window.outerHeight, // Current screen height
          });

          try {
            this.deviceData = JSON.parse(data);
          } catch (error) {
            console.error("Failed to parse JSON:", error);
          }

        }
      },
    
    
    //   generate new code 
    async generateNewCode() {
      try {
       await this.getDeviceData();
       console.log(this.deviceData);
       
        const response = await fetch('/generate-code', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': this.csrfToken
          },
           body: JSON.stringify(this.deviceData),
        });
        const data = await response.json();
        this.showDeviceCode(data.pin);
        this.setCookie('device_code', data.code, 365);
        this.setCookie('device_pin', data.pin, 365);
        this.initialChannels(data.code);
      } catch (error) {
        console.error('Error:', error);
      }
    },

    setDeviceConfig(device) {
      document.getElementById('main').style.transform = `rotate(${device.rotation}deg)`;
    },

    handleConnectionChange() {
      return navigator.onLine;
    },

    getCookie(name) {
      const value = `; ${document.cookie}`;
      const parts = value.split(`; ${name}=`);
      if (parts.length === 2) {
        const cookie = parts.pop().split(';').shift();
        return decodeURIComponent(cookie);  // Decode the URL-encoded cookie
      }
      return null;
    },

    setCookie(name, value, days) {
      const expires = new Date(Date.now() + days * 864e5).toUTCString();
      document.cookie = `${name}=${encodeURIComponent(value)}; expires=${expires}; path=/`;
    },

    deleteAllCookies() {
      const cookies = document.cookie.split("; ");
      for (let cookie of cookies) {
        const eqPos = cookie.indexOf("=");
        const name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
        document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/";
      }
    },




    async getDeviceInfo(deviceId) {
      
      console.log(deviceId);
      // Implement the API call to get playlists for the device
      try {
        const response = await axios.get(`/api/devices/deviceform/${deviceId}`);
       return response;
      } catch (error) {
        if (error.response) {
          // Server responded with a status other than 2xx
          console.error('Server Error:', error.response.status, error.response.data);
        } else if (error.request) {
          // No response was received from the server
          console.error('No response received:', error.request);
        } else {
          // Something went wrong in setting up the request
          console.error('Error setting up request:', error.message);
        }
      }
    },

    async storePlaylists(playlists) {
      
    },

    startFormOnScreen() {
       // Set showPin to false to hide ViewDeviceCode component
       this.showPin = false;

      //  check if the form have questions then start play 
      // if not then show error 
      if(this.form.questions.length>0)
      {
            // Set startPlay to true to show PlayForm component
          this.startPlay = true;
          this.currentStep=1;
          this.error=false;
        this.errorMessage=null;

      }
      else{
        this.currentStep=0;
        this.error=true;
        this.errorMessage="This Form do not have an questions";
      }
    },

    updateCurrentStep(step){
           this.currentStep=step;
  },

  
  

        
    }

    }
</script>

<style lang="scss" scoped>

</style>