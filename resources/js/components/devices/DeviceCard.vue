<template>
    <div class="grid">
        
        <div class="flex justify-between items-center space-x-4 rtl:space-x-reverse">
            <DeviceSvg :color="device.tag" />
            <!-- <span>{{ device.name }}</span> -->
            <div v-if="viewOptions" class="flex justify-between items-center ">
                <!-- delete action -->
                <DeleteAction 
                :title="translations.devices.delete_title"  
                :button-text="translations.buttons.delete" 
                :confirm-text="translations.devices.confirm_delete_message"
                :confirm-button-text="translations.buttons.confirm_delete_button_title" 
                :cancel-button-text="translations.buttons.cancel"
                :delete-action="() => deleteDevice(device.id)" />

                <!-- Refreash -->
                <RefreshSvg 
                :title="translations.devices.refresh_title" 
                @click="actionOnDevice(device.code, 'refresh')" />

                <!-- restart -->
                <RestartSvg :title="translations.devices.restart_title" @click="actionOnDevice(device.code, 'restart')" />

                <!-- edit device -->
                <EditAction :title="translations.devices.edit_title" @edit="editDevice()" />


            </div>
        </div>
       
        <div class="grid  items-center ">
            <!-- name -->
            <div class=" text-md font-bold">
                <!-- <span class="font-semibold">Device Name: </span> -->
                <span :title="device.name"
                class="hover:cursor-pointer whitespace-nowrap overflow-hidden text-ellipsis">{{ device.name }}</span>
            </div>
            <!-- remark -->
            <div class=" space-x-1 text-sm ">
                <!-- <span class="font-semibold ">Remark: </span> -->
                <span :title="device.app_version"
                class="hover:cursor-pointer whitespace-nowrap overflow-hidden text-ellipsis block max-w-[200px]">{{ device.app_version }}</span>
            </div>
        </div>

        

    </div>
</template>
<script>
import DeviceSvg from '../svgs/Device.vue';
import DeleteAction from '../actions/Delete.vue';
import EditAction from '../actions/Edit.vue';

import RefreshSvg from '../svgs/Refresh.vue';
import EditSvg from '../svgs/Edit.vue';


import RestartSvg from '../svgs/Restart.vue';
import { data } from 'autoprefixer';
import { MessageMixin } from '../mixins/MessageMixin';

export default {
    mixins: [MessageMixin], 

    components: {
        DeviceSvg,
        DeleteAction,
        RefreshSvg,
        EditSvg,
        EditAction,
        RestartSvg,
    },
    data(){
        return{
            loading:false,
            translations: window.translations,

        }
    },
    props: {
        device: {
            type: Object,
            required: true
        },
        viewOptions:{
            type:Boolean,
            default:true,
        }
    },
    methods: {
         deleteDevice(deviceId) {
            this.$emit('deletedevice',deviceId);
           
        },

        // this method can send event refresh for deivce by device code
        actionOnDevice(deviceCode, action) {
            console.log(deviceCode);
            axios.get(`/${action}-device/${deviceCode}`)
                .then(response => {
                    this.showAlert('success', response.data.message);
                })
                .catch(error => {
                    this.showAlert('error', error);
                });
        },

        editDevice(){
            this.$emit('edit');
        }
    }
};
</script>
