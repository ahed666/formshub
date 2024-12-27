<template>
    <div>
        <h1>{{ translations.devices.section_title }}</h1>
        <ul class="grid grid-cols-12 gap-2">
            <li class="shadow-md  m-2 max-h-max h-max  col-span-3  xl:col-span-3  lg:col-span-6 md:col-span-6 sm:col-span-12 xs:col-span-12 border-secondary_blue border-[1px] p-3 rounded-[0.5rem] bg-white" 
            v-for="device in devices" :key="device.id">
                <!-- card for device -->
                 <DeviceCard @deletedevice="emitDeleteDevice" @edit="openEditModal(device)" :device="device" />

                    
            </li>
        </ul>

        <ModalManager 
          v-if="isModalVisible"
          :device="selectedDevice"
          :typeModal="'editdevice'"
          @close="closeEditModal"
          @save="saveEditedDevice"
        
        />
       
    </div>

</template>

<script>

import ModalManager from '../modals/ModalManager.vue';
import { MessageMixin } from '../mixins/MessageMixin';
import DeviceCard from './DeviceCard.vue';

export default {
  mixins: [MessageMixin], 

    components:{
        
        ModalManager,DeviceCard
    },
    props: {
        devices: {
            type: Array,
            required: true
        },
        

    },
    data() {
    return {

      isModalVisible: false,
      selectedDevice: null,
      translations: window.translations,

    }
  },
    methods: {
        openEditModal(device) {
      this.selectedDevice = { ...device };
      this.isModalVisible = true;
    },
    closeEditModal() {
      this.isModalVisible = false;
    },
     saveEditedDevice(updatedDevice) {

        console.log('saving devices list');


        const index = this.devices.findIndex(device => device.id === updatedDevice.id);
        if (index !== -1) {
          this.devices.splice(index, 1, updatedDevice);
        }

        this.closeEditModal();
       
        this.showAlert('success','Device updated successfully!');



    },

    // emit to remove device after delete it 
    emitDeleteDevice(deviceId){
      this.$emit('deletedevice', deviceId); // Emit the deviceDeleted event
    }

   
   
    }
};
</script>

<style scoped>
/* Your component styles */
</style>
