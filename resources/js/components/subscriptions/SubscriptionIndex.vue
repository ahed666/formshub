<template>
  <app-template>
    <SubscriptionDetails />
    <!-- <Pricing /> -->

  </app-template>
</template>

<script>
import Pricing from './Pricing.vue';
import SubscriptionDetails from './SubscriptionDetails.vue';

  export default {
    components:{Pricing,SubscriptionDetails}
    
  }
</script>

<style lang="scss" scoped>

</style>