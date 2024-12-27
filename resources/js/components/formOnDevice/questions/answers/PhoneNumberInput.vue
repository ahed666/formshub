<template>
   
   <div>

   
    <div class="space-x-2 flex mb-2 items-center">
      <select
        v-model="selectedCountryCode"
        @change="validatePhoneNumber"
        class="mt-1 block w-2/5 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
      >
        <option disabled :value="null">Select Country Code</option>
        <option v-for="(country, index) in countryCodes" :key="index" :value="country">
          {{ country.name }} 
        </option>
      </select>
  
      <input
        type="tel"
        v-model="inputText"
        @input="validatePhoneNumber"
        :class="[{
          'border-red-500': phoneError,
          'border-gray-300': !phoneError
        } ,'border rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 mt-1 w-3/5 p-2']"
        placeholder="Enter your phone number"
      />
      <span v-if="phoneError" class="text-red-600 text-sm">{{ phoneError }}</span>
    </div>
    <NumberKeyboard  :enable="enableNumberKeyboard"  @key-press="addToInput" @backspace="removeLastCharacter"
     />

    </div>


  </template>
  
  <script>
  import { COUNTRIES } from '../../../../data/countries';
  import { parsePhoneNumber } from 'libphonenumber-js';
import NumberKeyboard from './NumberKeyboard.vue';
  
  export default {
    data() {
      return {
        inputText: '',
        phoneError: '',
        selectedCountryCode:null,
        enableNumberKeyboard:false,
        countryCodes: Object.values(COUNTRIES ).map(country => ({
          name: country.name,
          code: country.code,
          mobileCode:country.mobileCode,
        })),
      };
    },
    
    components:{
        NumberKeyboard
    },
    watch:{
        selectedCountryCode:{
            handler(newVal) {
                this.enableNumberKeyboard=newVal!=null?true:false;
                

            }
        }
    },
    mounted(){
     
    },
    methods: {
     
        addToInput(key) {
            this.inputText += key;
             this.validatePhoneNumber();

        },
        removeLastCharacter() {
            this.inputText = this.inputText.slice(0, -1);
            this.validatePhoneNumber();
        },

      validatePhoneNumber() {
        this.phoneError = '';
        
        if (!this.inputText) {
          this.phoneError = 'Phone number is required';
        } else {
          const phoneNumber = parsePhoneNumber(this.inputText, this.selectedCountryCode.code);
          
          if (!phoneNumber || !phoneNumber.isValid()) {
            this.phoneError = 'Invalid phone number';
            this.$emit('update:error');

          } else {
            this.phoneError = null;
            this.$emit('update:phoneNumber', this.selectedCountryCode.mobileCode + '-' + this.inputText);
          }
        }
      },
    },
  };
  </script>
  
  <style scoped>
  .error-message {
    color: red;
  }
  </style>
  