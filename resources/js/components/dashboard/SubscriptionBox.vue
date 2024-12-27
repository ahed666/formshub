<template>
    <div class="grid justify-center items-center p-2">
        <div :class="['rounded p-2 text-center', getStatusClass(subscription.status)]">
            {{ subscription.status }}
        </div>
        <div v-html="createDaysLeftText(subscription.daysleft)" class="text-center">
            
        </div>

         
    </div>
</template>

<script>
  import { SubscriptionMixin } from '../mixins/Subscription.Mixin';
    export default {
        mixins: [SubscriptionMixin], 
        props:{
            subscription:{
                type:Object,
            },
        },
        methods:{},

    }
</script>

<style lang="scss" scoped>

</style>