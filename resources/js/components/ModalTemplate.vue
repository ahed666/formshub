<!-- ModalTemplate.vue -->
<template>
    <div  class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block self-center bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all
        2xl:max-w-screen-lg   md:max-w-screen-md  sm:my-8 sm:align-middle  sm:max-w-lg sm:w-full">
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:justify-center sm:items-center text-center sm:mt-0 sm:text-left">
            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
              {{ title }}
            </h3>
          </div>

            <Loader v-if="isSaving"/>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">

                    <div class="grid grid-cols-12 justify-center items-center sm:items-start">
                    <slot></slot>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 md:flex xs:flex-wrap space-x-4 
                rtl:space-x-reverse justify-between ">

                  
                    <div class="flex md:space-x-4 xs:space-x-2 rtl:space-x-reverse justify-between items-center" v-if="extraInfo && step==2">
                      <!-- question status (optional or not ) -->
                          <div class="grid justify-center items-center" v-if="info.questionOptionalComp">
                            <span class="text-xs font-bold">{{ info.questionOptional?"Optional":"Mandetory" }}</span>
                            <ToggleSwitch  :value="info.questionOptional" @toggle="toggleQuestionOptional"></ToggleSwitch>

                          </div>
                          <div class="grid" v-if="info.questionViewAnswersMode">
                            <label class="text-xs font-bold"or="view-mode">{{ translations.titles.answers_view_mode_title }}</label>
                            <select class="p-[1px] rounded-lg h-6 text-sm" v-model="selectedMode" @change="onSelectionViewModeChange" id="view-mode">
                              <option v-for="(entry, index) in questionViewAnswersModeTypes" :key="index" :value="getKey(entry)">
                                {{ getValue(entry) }}
                              </option>
                            </select>
                          </div>
                          <!-- button -->
                          <div  v-if="info.buttonMode">
                            <ButtonComponent :btnClass="'bg-secondary_blue  w-14'" :handleClick="info.handleButtonClick" >
                              {{ info.buttonTitle }}
                            </ButtonComponent>
                          </div>
                          
                          <div class=" flex justify-center" v-if="errorMessage">
                                <span class="text-xs text-primary_red  p-2 rounded-lg ">
                                  {{ errorMessage }}
                                </span>
                          </div>
                    </div>
                        
                    <!-- cancel and save button -->
                    <div class="flex space-x-2 justify-center rtl:space-x-reverse items-center md:mt-0 xs:mt-2">
                      <ButtonComponent :btnClass="'bg-gray-400  w-14'" :handleClick="closeModal"  >
                        {{translations.buttons.cancel}}
                      </ButtonComponent>
                     
                      <ButtonComponent v-if="showButtons" :disabled="disableButtons"  
                      :btnClass="'bg-secondary_blue  w-20'" :handleClick="handleButtonClick"  >
                          <span v-if="isSaving" class="mr-2">{{translations.titles.saving}}</span>
                          <span v-else >
                              
                              <span v-if="step==2" >{{translations.buttons.save}}</span>
                              <span v-else-if="step==1">{{translations.buttons.next}}</span>
                          </span>
                      </ButtonComponent>
                    </div>
                         
                   
                </div>

        </div>
      </div>
    </div>
  </template>

  <script>
  import { errorMessages } from '@vue/compiler-sfc';
  import ToggleSwitch from './actions/ToggleSwitch.vue';
  import ButtonComponent from './actions/ButtonComponent.vue';
  import Loader from './svgs/Loader.vue';
import { info } from 'autoprefixer';
  export default {
    components:{
        ButtonComponent,
        ToggleSwitch,
        Loader,
    },
    props: {
      extraInfo:{
        type:Boolean,
        required:false,
        default:false,
      },
      info:{
        type:Object,
        required:false,
        
      },
      errorMessage:{
       type:String,
       required:false,
      },
      step:{
        type:Number,
        required: false,
        default:2,
      },
      
      title: {
        type: String,
        default: ''
      },
      isSaving: {
      type: Boolean,
      default: false,

    },
    disableButtons:{
        type:Boolean,
        default:false,

    },
    showButtons:{
        type: Boolean,
        default: true
    },
    allowSelect: {
      type: Boolean,
      default: false,
    },
    questionViewAnswersModeValue:{
      type:String,
      required:false,
    }
    },
    data(){
      return {
        selectedMode: '1_column', // Will store the user's selected value
        translations:window.translations,
        questionViewAnswersModeTypes:[],
      }
    },
    created(){
      this.questionViewAnswersModeTypes=[
          {'1_column':this.translations.titles.one_column},{'2_columns':this.translations.titles.two_columns},{'3_columns':this.translations.titles.three_columns}
        ]
    },
    watch: {
      info: {
            immediate: true, // Trigger immediately
            handler(newVal) {
              console.log('new value',newVal);
                this.selectedMode =newVal==null?null:newVal.questionViewAnswersModeValue ;
            },
        },
        
    },
    methods:{
      getKey(entry) {
      // Extract the key from the object
      return Object.keys(entry)[0];
    },
    getValue(entry) {
      // Extract the value from the object
      return Object.values(entry)[0];
    },
      onSelectionViewModeChange() {
      // Emit the selected mode to the parent
      this.$emit('view-mode-changed', this.selectedMode);
    },
        closeModal() {
        this.$emit('close');
      },
      submitForm() {
          console.log('submit from template');
       this.$emit('submit');

      },
       // Conditionally handle the button click
      handleButtonClick() {
        if (this.step === 1) {
          this.$emit('next');
        } else if (this.step === 2) {
          this.submitForm();
        }
      },
      toggleQuestionOptional(){
          this.$emit('toggleQuestionStatus',!this.info.questionOptional);
      }
    }
  };
  </script>

  <style scoped>
  /* Add any additional styling here */
  </style>
