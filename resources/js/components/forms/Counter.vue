<!-- Modal.vue -->
<template>
    <div>
     <span v-if="count>0" 
        class="text-sm text-secondary_blue font-bold rounded-lg px-2 py-1 ">
                             {{ count }} {{ count>1?unit[0]:unit[1] }}
                           </span>
                           <span v-else class="text-xs text-primary_red">
                           {{ zeroCountText }}
                           </span>
    </div>
 </template>
 
 <script>
 
 export default {
     name: 'Counter',
     components: {
    
     },
     props: {
         count: {
             type: Number,
             required: true
         },
         unit:{
            type:Array,
         },
         zeroCountText:{
            type:String,

         }

         
 
     },
     data() {
         return {
             rtl:window.appLocale=='ar',
         };
 
     },
     mounted() {
        
     },
     methods: {
 
        
     }
 };
 </script>
 
 <style scoped>
 /* Add any scoped styles here */
 </style>
 