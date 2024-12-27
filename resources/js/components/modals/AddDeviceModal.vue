<!-- Modal.vue -->
<template>
     <modal-template
     :isSaving="isSaving"
      @submit="saveDevice" @close="closeModal" :isVisible="isVisible" :title="result?'Add Device':'Enter valid pin that apper on screen'">



                <div class="col-span-12 grid justify-center">
                    <div class="flex justify-center items-center space-x-4 rtl:space-x-reverse">
                        <div class="flex justify-center items-center space-x-2 rtl:space-x-reverse">
                            <div v-for="(digit, index) in pin" :key="index">
                            <input
                                :value="digit"
                                type="text"
                              @keypress="isDigit"
                                maxlength="1"
                                :disabled="result"
                                  :readonly="result"
                                v-model="pin[index]"
                                @input="focusNext(index+1)"
                                :ref="'pin' + (index+1)"
                                class="w-12 h-12 text-center border rounded focus:outline-none"
                            />
                            </div>

                        </div>
                        <div class="flex justify-center items-center" v-if="isSubmitted">

                             <checkedSvg  v-if="result" />
                             <errorSvg v-if="!result"/>

                        </div>
                    </div>
                    <div class="my-2" v-if="isSubmitting">{{ submittingStatusMessage }}</div>

                    <div class="mt-4" v-if="result">
                        <input v-model="deviceName" type="text" maxlength="30" class="w-full border rounded px-3 py-2 my-1" placeholder="Device Name">
                        <div v-if="validationMessage" class="text-red-500 text-sm mt-1">{{ validationMessage }}</div>

                        <div class="mt-2">
                        <!-- <input v-model="remark" type="text" maxlength="40" class="w-full border rounded px-3 py-2 my-1" placeholder="Remark"> -->
                        
                        <!-- color tag -->
                        <div class="mt-2">
                            <label>{{ translations.devices.select_tag_color_title }}</label>
                            <div class="flex space-x-2 rtl:space-x-reverse mt-2">
                            <div
                                v-for="(color, index) in tagColors"
                                :key="index"
                                :style="{ backgroundColor: color }"
                                class="w-8 h-8 rounded-full cursor-pointer border-2"
                                :class="{ 'border-black': selectedTagColor === color }"
                                @click="selectTagColor(color)"
                            ></div>
                            </div>
                        </div>

                        <!-- rotate degree -->
                        <div class="mt-2">
                            <label>{{ translations.devices.select_rotation_degree_title }}</label>
                            <div class="flex space-x-2 rtl:space-x-reverse mt-2">
                            <div
                                v-for="(degree, index) in degrees"
                                :key="index"

                                class="w-14 h-10 flex justify-center items-center rounded-lg cursor-pointer border-2"
                                :class="{ 'border-secondary_blue': selectedRotation === degree }"
                                @click="selectRotation(degree)"
                            ><span class="text-center"> {{ degree }}</span></div>
                            </div>
                        </div>

                       </div>
                    </div>
                </div>



        </modal-template>

  </template>

  <script>
  import checkedSvg from '../svgs/Checked.vue';
  import errorSvg from '../svgs/Error.vue';
  import ModalTemplate from '../ModalTemplate.vue';
  import { MessageMixin } from '../mixins/MessageMixin';

  export default {
    mixins: [MessageMixin], 

    name: 'AddDeviceModal',
    components: {
    checkedSvg,
    errorSvg,
    ModalTemplate,

  },
    props: {
      isVisible: {
        type: Boolean,
        required: true
      }
    },
    data() {
      return {
        deviceName: '',
        remark:'',
        tagColors: ['#FF0000', '#00FF00', '#0000FF', '#FFFF00', '#FFA500','#000000'], // Add more colors as needed
        selectedTagColor: '#000000',
        selectedRotation:0,
        isSubmitting: false,
        submittingStatusMessage:'',
        result:false,
        isSubmitted:false,
        validationMessage:'',
        currentPin:null,
        pin: ['', '', '', ''],
        degrees:[0,90,180,270],
        translations: window.translations,
        isSaving:false,

      };

    },
    methods: {
    // select tag color for device
    selectTagColor(color) {
      this.selectedTagColor = color;
    },
    selectRotation(degree){
        this.selectedRotation = degree;

    },

    // the pattern only accept 0-9 digits
    isDigit(event) {
      const charCode = event.which ? event.which : event.keyCode;
      if (charCode < 48 || charCode > 57) {
        event.preventDefault();
      }
    },

    // close modal
    closeModal() {
    this.pin= ['', '', '',''];
    this.deviceName='';
    this.remark='';
    this.isSubmitting= false;
    this.submittingStatusMessage='';
    this.result=false;
    this.isSubmitted=false;
    this.currentPin=null;

    this.$emit('close');
    },

    // used to focus on next input of pin inputs,when enter digit of current pin input then make focus on next
    focusNext(index,)
    {
        console.log(this.$refs,index);
        if(this.pin.some(item => item === '')==false&&index===4)
     this.submitPin();
       else if(index < 4)
      {  const nextInput=this.$refs[`pin${index+1 }`][0];
        nextInput.setSelectionRange(0, 1);
        nextInput.focus();}
    },
    // to submit pin after user enter it
      submitPin() {
        this.isSubmitting = true;
        this.submittingStatusMessage = 'Submitting your PIN...';
        this.currentPin = this.pin.join('');
        // Implement logic to submit PIN (e.g., call route to check device availability)
        console.log('Submitting PIN:', this.currentPin);
        // Example: Call your Laravel route to check device availability
        axios.get(`/check-avilable-device/${this.currentPin}`)
                  .then(response => {
                      console.log(response.data);
                      this.isSubmitting = false;

                      this.result=response.data.message;
                      this.isSubmitted=true;
                      if(this.result==false)this.focusNext(0);
                  })
                  .catch(error => {


                      console.error(error);
        });

      },
    // save device function
    async  saveDevice() {

        //  this result true mean if there is deivce code avilable in table and the pin is correct
        if(this.result)
        {
            console.log(this.deviceName);
            if (!this.deviceName ) {
            this.validationMessage = 'Please type device name';
            return;
            }
            // call save function
            // this.$emit('save',this.currentPin,this.Remark,this.selectedTagColor, this.deviceName);
            try {
              this.isSaving=true;
        const response = await axios.post('/add-device', {
          name: this.deviceName,
          remark: this.remark,
          form_id:null,
          pin:this.currentPin,
          tagColor:this.selectedTagColor,
          selectedRotation:this.selectedRotation,
        });
        // if can add device with allow feature (add device) in subscription
        if(response.data.can)
        { this.closeModal();
          console.log('Device added:', response.data);

          this.$emit('saved', response.data.device,response.data.message);
        }
        // else cannot the show error message
        else
        {this.showAlert('error', response.data.message);
        this.$emit('close');}

         // Emit the saved event

            // Handle success, perhaps clear the form or show a success message
        } catch (error) {
            console.error('Error adding device:', error);
            this.validationMessage = 'Failed to add device.';
        }
        finally{
          this.isSaving=false;
        }


        }

      }
    }
  };
  </script>

  <style scoped>
  /* Add any scoped styles here */
  </style>
