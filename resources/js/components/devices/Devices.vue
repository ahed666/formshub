<template>
     <app-template>
      <Loader v-if="isLoading" />
                <headerpage>

                    <ButtonComponent :disabled="loadingModal" :btnClass="'bg-secondary_blue hover:bg-blue-700'" @click="showModal()">
                      <template v-if="loadingModal">
                      <LoadSvg />
                    </template>
                    <template v-else>
                      {{ translations.devices.add }}
                    </template>
                    </ButtonComponent>

                </headerpage>

                <DeviceList :maxPlaylistsNum="maxPlaylistsNum" :devices="devicesData" @deletedevice="deleteDevice"></DeviceList>


                <!-- add device  modal -->
                 
                <ModalManager 

                v-if="isModalVisible"
                :userCanAdd="userCanAddDevice"
                :errorMessage="errorAddMessage"
                :typeModal="'adddevice'"
                 @close="hideModal" 
                 @saved="addDeviceToList"
                
                />

               

            </app-template>

</template>
<script>
import ButtonComponent from '../actions/ButtonComponent.vue';
import DeviceList from './DeviceList.vue';
import ModalManager from '../modals/ModalManager.vue';
import LoadSvg from '../svgs/Load.vue';
import Loader from '../svgs/Loader.vue';
import { MessageMixin } from '../mixins/MessageMixin';

export default {
  mixins: [MessageMixin], 

  components: {
    ButtonComponent,
    DeviceList,
    ModalManager,
    LoadSvg,
    Loader,

  },
  props: {
        devices: {
            type: Array,
            required: true
        },
        maxPlaylistsNum:{
            type: Number,
            required: true,
        },
    },
  data() {
    return {
        isModalVisible: false,
        userCanAddDevice:false,
        errorAddMessage:null,
        loadingModal:false,
        isLoading:false,
        devicesData:[],
        translations: window.translations,
    };
  },
  methods: {
   async showModal() {
       try {
        const response=await axios.get('/canusermakeaction/adddevice');
        this.loadingModal=true;
        console.log(response.data);
                this.userCanAddDevice=response.data.result;
                this.errorAddMessage=response.data.resultmessage;
                console.log(this.errorAddMessage);
                this.isModalVisible = true;
                this.loadingModal=false;
       } catch (error) {
        this.isModalVisible = false;
        this.loadingModal=false;
       }
     
      
    },
    hideModal() {
      console.log('close',this.isModalVisible);

      this.isModalVisible = false;
      console.log(this.isModalVisible);

    },
    // add new device to devices list after add device
    addDeviceToList(newDevice,message) {
      console.log('add to list :',message);
        this.devicesData.push(Object.assign({}, newDevice));
        this.showAlert('success',message);


    },
    // delete device
  async  deleteDevice(deviceId){
      try {
                this.isLoading=true;
                // Call your delete API here
                const response = await axios.delete(`/deletedevices/${deviceId}`);

                // Remove the device from the devices array
                this.removeDeviceFromList(deviceId);
                this.isLoading=false;
                this.showAlert('success',this.translations.devices.successdeletedevice); 

            } catch (error) {
                this.showAlert('error','Failed to delete the device.'); 
                this.isLoading=false;
                

            }
    },
    removeDeviceFromList(deviceId) {
      console.log('delete:',this.devicesData,deviceId);
     this.devicesData = this.devicesData.filter(device => device.id !== deviceId);
     console.log('deleted:',this.devicesData);
    },


},


    mounted() {
        this.devicesData = this.devices;
        console.log('mounted:',this.devicesData);
    },
    watch: {
        devices(newDevices) {
        this.devicesData = newDevices;
    }},

};
</script>
