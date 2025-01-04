<template>
  <div v-if="translations" class="py-8"><div class="border-t border-gray-200"></div></div>
    <div class="md:grid md:grid-cols-3 md:gap-6">
      <!-- title -->
      <div class="md:col-span-1 flex justify-between">
        <div class="px-4 sm:px-0">
          <h3 class="text-lg font-medium text-gray-900">{{translations.profile.billing_info_title}}</h3>
          <p class="mt-1 text-sm text-gray-600"> 
            {{translations.profile.billing_info_desc}}</p>
          </div>
          <div class="px-4 sm:px-0"></div>
      </div>
      
      <!-- form -->
      <div class="mt-5 md:mt-0 md:col-span-2">
        <form @submit.prevent="updateBilling">
          <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
            <div class="grid grid-cols-6 gap-6">
              <!-- billing name -->
              <div class="col-span-6 sm:col-span-4">
                <label class="block font-medium text-sm text-gray-700" for="billing_name">{{translations.profile.biiling_name_title}}</label>
                <input class="border-gray-300 focus:border-indigo-500
                  focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" 
                  type="text"
                  id="billing_name"
                  v-model="billing.billing_name" >
              </div>
              <!-- Country -->
              <div class="col-span-6 sm:col-span-4">
                <label class="block font-medium text-sm text-gray-700" for="billing_country"> {{translations.profile.country_title}} </label>
                <select
                  id="billing_country"
                  v-model="billing.billing_country"
                  class="w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-300"
                >
                  <option value="">Select Country</option>
                  <option v-for="country in countries" :key="country.country_code" :value="country.country_code">
                    {{ country.country }}
                  </option>
                </select>
              </div>
              <!-- billing city -->
              <div class="col-span-6 sm:col-span-4">
                <label class="block font-medium text-sm text-gray-700" for="billing_city"> {{translations.profile.city_title}} </label>
                <input class="border-gray-300 focus:border-indigo-500
                  focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" 
                  type="text"
                  id="billing_city"
                  v-model="billing.billing_city" >
              </div>
              <!-- billing trn -->
              <div class="col-span-6 sm:col-span-4">
                <label class="block font-medium text-sm text-gray-700" for="billing_trn"> {{translations.profile.tax_title}}</label>
                <input class="border-gray-300 focus:border-indigo-500
                  focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" 
                  type="text"
                  id="billing_trn"
                  v-model="billing.billing_trn" >
              </div>
              
            </div>
          </div>
            <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-end sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
              
              <button type="submit" 
                class="w-20 bg-secondary_blue text-white text-sm rounded        p-1 h-auto  text-center hover:cursor-pointer
    ease-in delay-100  hover:-translate-z-1 hover:scale-[1.1]  duration-200   disabled:bg-gray-400 " > 
    {{translations.buttons.save}} 
              </button>
            </div>
        </form>
      </div>
      
    </div>
  </template>
<script>
import axios from 'axios';
import { MessageMixin } from '../mixins/MessageMixin';
export default {
  mixins:[MessageMixin],
  data() {
    return {
      billing: {
        billing_name: '',
        billing_country: '',
        
        billing_city: '',
        billing_trn: '',
        translations:window.translations,
      },
      countries: [], // Will be fetched from the backend
    };
  },
  methods: {
    // fetching billing info
    async fetchBillingInfo() {
        console.log('fetching billing info');
      try {
        const response = await axios.get('/getuser');
        this.billing = response.data;
        console.log(this.billing,'response:',response);
      } catch (error) {
        console.error('Error fetching billing info:', error);
      }
    },
    // fetch countries
    async fetchCountries() {
        console.log('fetching countries');
      // try {
        const response = await axios.get('/countries');
        this.countries = response.data;
        console.log(this.countries,'response:',response);
      // } catch (error) {
      //   console.error('Error fetching countries:', error);
      // }
    },
    // update billing
    async updateBilling() {
      try {
        await axios.post('/customer/update-billing', this.billing);
      
        this.showAlert('success',this.translations.billing_details_updated_success_message);
      } catch (error) {
        console.error('Error updating billing info:', error);
        
        this.showAlert('error',billing_details_updated_error_message);
      }
    },
  },
  mounted() {
    this.fetchBillingInfo();
    this.fetchCountries();
  },
};
</script>
  