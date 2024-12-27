<template>
    <div class="flex justify-between items-center py-2" :class="[{'border-b-[2px] border-gray-300':withBottomBorder}]">
      <h1 class="text-md">{{ title }}</h1>
      <div class="flex items-center  space-x-1 rtl:space-x-reverse" :class="[{'w-full max-w-[50%]':withProgressBar}]">
        <ProgressBar v-if="withProgressBar" :percent="count" />
        <h1 :class="['font-bold text-md flex space-x-1 rtl:space-x-reverse p-1 rounded',countClass] ">{{ count }} <span v-if="unit" class="m-x-1">{{ unit }}</span> </h1>
      </div>
    </div>
</template>

<script>
import ProgressBar from './ProgressBar.vue';

    export default {
        components:{
            ProgressBar
        },
        props:{
            title:{
                type:String
            },
            count:{
                type:Number,
            },
            unit:{
                type:String,
                Required:false
            },
            withBottomBorder:{
               type:Boolean, 
               default:false,
            },
            withProgressBar:{
               type:Boolean, 
               default:false,
            },
            countClass:{
                type:String,
                default:false,
            },
        },
    }
</script>

<style lang="scss" scoped>

</style>