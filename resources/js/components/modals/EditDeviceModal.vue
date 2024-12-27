<!-- EditDeviceModal.vue -->
<template>
    <modal-template :isSaving="isSaving" @submit="submitForm" 
    @close="closeModal" 
        title="Edit Device">


        <!-- device settings -->
        <div class="mt-4 2xl:col-span-6 md:col-span-6 xs:col-span-12 border-r-[1px] border-gray-300 p-2">
            <input v-model="localDevice.name" type="text" maxlength="30" class="w-full border rounded px-3 py-2 my-1"
                placeholder="Device Name">
            <div v-if="validationMessage" class="text-red-500 text-sm mt-1">{{ validationMessage }}</div>

            <div class="mt-2">
                <!-- <input v-model="localDevice.remark" type="text" maxlength="40"
                    class="w-full border rounded px-3 py-2 my-1" placeholder="Remark"> -->
                <!-- color tag -->
                <div class="mt-2">
                    <label>{{ translations.devices.select_tag_color_title }}</label>
                    <div class="flex space-x-2 rtl:space-x-reverse mt-2">
                        <div v-for="(color, index) in tagColors" :key="index" :style="{ backgroundColor: color }"
                            class="w-8 h-8 rounded-full cursor-pointer border-2"
                            :class="{ 'border-black': localDevice.tag === color }" @click="selectTagColor(color)"></div>
                    </div>
                </div>

                <!-- rotate degree -->
                <div class="mt-2">
                    <label>{{ translations.devices.select_rotation_degree_title }}</label>
                    <div class="flex space-x-2 rtl:space-x-reverse mt-2">
                        <div v-for="(degree, index) in degrees" :key="index"
                            class="w-14 h-10 flex justify-center items-center rounded-lg cursor-pointer border-2"
                            :class="{ 'border-secondary_blue': localDevice.rotation == degree }"
                            @click="selectRotation(degree)"><span class="text-center"> {{ degree }}</span></div>
                    </div>
                </div>

            </div>


        </div>
        <!-- playlists  -->
        <div class="2xl:col-span-6 md:col-span-6 xs:col-span-12 p-2 " :class="{ 'animate-pulse': isLoading }">
            <div class="flex justify-center items-center mx-1">
                <h1 class="text-sm"> Select Forms</h1>
            </div>

            <!-- select form for device -->
             <SelectDeviceForm
             :deviceFormId="localDevice.form_id"
             @setForm="SetFormToDevice"
             ></SelectDeviceForm>

            
        </div>

    </modal-template>
</template>

<script>
import ModalTemplate from '../ModalTemplate.vue';
import Loader from '../svgs/Loader.vue';
import SelectDeviceForm from '../devices/SelectDeviceForm.vue';
export default {
    name: 'EditDeviceModal',
    components: { ModalTemplate, Loader,SelectDeviceForm},
    data() {
        return {
            playlists: [],
            isLoading: false,
            tagColors: ['#FF0000', '#00FF00', '#0000FF', '#FFFF00', '#FFA500', '#000000'], // Add more colors as needed
            localDevice: { ...this.device },
            isSaving: false,
            validationMessage: '',
            selectedPlaylists: [],
            degrees: [0, 90, 180, 270],
            translations:window.translations,
        };

    },


    props: {
        isOpen: {
            type: Boolean,
            required: true
        },
        device: {
            type: Object,
            required: true
        },
        
    },
    methods: {
       
        // set form for device
        SetFormToDevice(id){

            this.localDevice.form_id=this.localDevice.form_id==id?null:id;
            
            

            console.log(this.localDevice);

        },
        closeModal() {
            this.selectedPlaylists = [];
            this.$emit('close');
        },
        async submitForm() {

            if (this.localDevice.name == '') {
                this.validationMessage = 'Please type device name';
                return;
            }
            
            this.isSaving = true;
            console.log('saving edit device',this.localDevice);
            try {
                const response = await axios.post('/edit-device', {
                    device: this.localDevice,
                });
                console.log(response);
                if (response.status === 200) {
                    console.log('response', response);
                    this.$emit('save', this.localDevice);
                    this.validationMessage = '';
                    this.showAlert('success', response.data.message);
                }
                else {
                    console.log(response.data);
                    this.showAlert('error', response.data.message);
                }

            } catch (error) {
                this.showAlert('error', 'Failed to update device!'.response.data.message);

            }


            this.isSaving = false;
            this.selectedPlaylists = [];
        },
        // show alert warning or success
        showAlert(type, title) {
            this.$swal.fire({
                position: 'top-end',
                icon: type,
                title: title,
                showConfirmButton: false,
                timer: 3500
            });
        },
        selectTagColor(color) {
            this.localDevice.tag = color;
        },
        selectRotation(degree) {
            this.localDevice.rotation = degree;
        }
    },
    watch: {
        device: {
            immediate: true,
            handler(newVal) {
                this.localDevice = { ...newVal };
                
            }
        }
    }

}
</script>

<style scoped>
/* Add any necessary styles here */
</style>
