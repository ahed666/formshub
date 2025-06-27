<template>
  
    <div  v-if="subscriptionDetails" :class="['bg-white p-4 rounded-lg border-[1px]',{'animate-pulse':loading}]">
    <div class="text-center text-lg font-semibold text-gray-700">{{translations.subscriptions.subscription_details_title}}</div>

    
    
    <div class="flex justify-between space-x-4 rtl:space-x-reverse items-center md:w-1/2 xs:w-full ">
      <InfoLine class="md:w-1/2 xs:w-full" :countClass="getStatusClass(subscriptionDetails.status)" :title="'Status:'" :count="subscriptionDetails.status"  />

      <DropdownMenu :loading="loading" @action-selected="handleDropdownMenuAction"  :options="SubscriptionDropdownOptions" />

    </div>
    
    <InfoLine class="md:w-1/2 xs:w-full" :title="'Start Date:'" :count="subscriptionDetails.start_date"  />
    <InfoLine class="md:w-1/2 xs:w-full" :title="'End Date:'" :count="subscriptionDetails.end_date"  />
    <InfoLine class="md:w-1/2 xs:w-full" :title="'Next Billing:'" :count="subscriptionDetails.next_billing"  />
    <InfoLine class="md:w-1/2 xs:w-full" :title="'Consumed:'" :withProgressBar="true" :unit="'%'" :count="subscriptionDetails.consumption_percent"  />
   

   
  </div>
  
  <div v-else-if="loading&&subscriptionDetails==null" class="bg-white p-4 rounded-lg border-[1px]">
    <div class="text-center text-lg font-semibold">{{translations.titles.loading_data}} </div>

  </div>
  <div v-else class="bg-white p-4 rounded-lg border-[1px]">
    <div class="text-center text-lg font-semibold text-red-400">{{ translations.subscriptions.no_subscription }} </div>

  </div>
  <Loader v-if="loading" />
 
</template>

<script>
import ProgressBar from '../forms/statistics/components/ProgressBar.vue';
import InfoLine from '../forms/statistics/components/InfoLine.vue';
import { MessageMixin } from '../mixins/MessageMixin';
import { SubscriptionMixin } from '../mixins/Subscription.Mixin';
import DropdownMenu from '../actions/DropdownMenu.vue';
import Loader from '../svgs/Loader.vue';
import { loadStripe } from '@stripe/stripe-js';

  export default {
    mixins: [MessageMixin,SubscriptionMixin], 

    components:{ProgressBar,InfoLine,DropdownMenu,Loader},

    data(){
      return{
        SubscriptionDropdownOptions:[],
        subscriptionDetails:null,
        loading:false,
        translations:window.translations,
      }
    },

    mounted(){
      this.getSubscriptionDetails();
    },

    methods: {

      // handle dropdown action
      handleDropdownMenuAction(action){
        // this.loading=false;
        if (this[action]) {
          this[action]();
        } 
        // this.loading=true;
      },

      // get subscription details
      async getSubscriptionDetails() {
        this.loading=true;
        try {
          
          const response = await axios.get('/getcurrentsubscriptiondetails');
          console.log(response);
          this.subscriptionDetails = response.data;
          this.initialSubscriptionMenuOptions();
        } catch (error) {
          this.showAlert('error', this.translations.titles.error_title);
        }finally {
          this.loading = false; // Ensure loading is set to false regardless of success or failure
          
        }
      },


      // initial subscription dropdown menu options
      initialSubscriptionMenuOptions(){
        this.SubscriptionDropdownOptions=[
          { type: 'button', view:this.subscriptionDetails.status=='trialing', 
          label: this.translations.buttons.billnow, action: 'billNow','buttonPos':true },

          { type: 'button',view:this.subscriptionDetails.status=='active', 
          label: this.translations.buttons.cancel, action: 'cancelSubscription','buttonPos':false },

          { type: 'button',view:this.subscriptionDetails.status=='canceled', 
          label: this.translations.buttons.renew, action: 'billNow','buttonPos':true },

          { type: 'toggle_switch', value :!this.subscriptionDetails.cancelOnPeriodEnd ,
          view:this.subscriptionDetails.status!='canceled',
          labelTrue: this.translations.subscriptions.auto_renew_enabled, 
          labelFalse:this.translations.subscriptions.auto_renew_disabled, action: 'updateAutoRenew' },
          

        ];
      },

      
      // renew the subscription after expired
      async billNow() {
        // Handle plan selection logic (e.g., show checkout modal or redirect to checkout page)
        this.loading = true;
        try {
          // const stripe = await loadStripe('pk_test_51QIzedKw4tv10ve9Duwad0Dohk4Awh66Lvn4jnUclw6AZcRyvF6DoWV56S44Q3rV5ebG7Pck4coFWa33yuwn49gf00FWlHAqAQ');  // Replace with your Stripe publishable key
          const response = await axios.post('/subscription-checkout',
            {
              product_id: this.subscriptionDetails.stripe_product_id,
              price_id: this.subscriptionDetails.stripe_price_id,
            }
          );
            console.log(response);
          // const response = await axios.post(`/create-payment-session/${this.subscriptionDetails.subscription_Stripe_id}`);
          window.location.href = response.data.url;

          // Redirect to Stripe Checkout using the session ID
          // const { error } = await stripe.redirectToCheckout({ sessionId });

          // window.location.href = response.data.url;
        } catch (error) {
          console.log(error);
          this.showAlert('error', this.translations.subscriptions.error_while_payment,error);
        }finally{
          this.loading = false;
        }

        
      },

      async updateAutoRenew(){
        this.loading = true;
        try {
          const response = await axios.post('/subscription/updateautorenew', {
            subscription_id: this.subscriptionDetails.subscription_Stripe_id,
          });
          
          console.log(response,this.subscriptionDetails.cancelOnPeriodEnd);
          this.subscriptionDetails=response.data.subscription_details;
          this.initialSubscriptionMenuOptions();
          this.showAlert('success', response.data.message);

          console.log(this.subscriptionDetails.cancelOnPeriodEnd);

        } catch (error) {
          if (error.response) {
            this.showAlert('error', error.response.data.message);
          } else {
            this.showAlert('error ', error.response.data.message);
          }
        }finally{this.loading = false;}
      },

      // view cancel subscription warning 
      cancelSubscription(){
        this.showWarning(this.confirmCancelSubscription,this.translations.subscriptions.cancel_subscription_warning,this.translations.subscriptions.confirm_cancel_title,this.translations.buttons.cancel);
      },

      // cancel subscription
      async confirmCancelSubscription() {
        this.loading = true;
        try {
          const response = await axios.post('/subscription/cancel', {
            subscription_id: this.subscriptionDetails.subscription_Stripe_id,
          });
          
          console.log(response);
          this.showAlert('success', response.data.message);
          this.getSubscriptionDetails();


        } catch (error) {
          if (error.response) {
            this.showAlert('error', error.response.data.message);
          } else {
            this.showAlert('error ', error.response.data.message);
          }
        }finally{this.loading = false;}
      },
      
    },
    
  }
</script>

<style lang="scss" scoped>

</style>