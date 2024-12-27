<!-- ModalTemplate.vue -->
<template>
   
    <div class=" flex justify-center space-x-2 rtl:space-x-reverse items-center ">
        <img class="w-[400px] h-[300px] xs:w-[200px] xs:h-[200px] object-contain" :src="'/images/questions_text_images/'+questionType.type_text+'.png'" alt="">
    </div>
   
   
 </template>

 <script>


 export default {
   components:{
       
   },
   props: {
      
       questionType:{
         type:Object,
         required:false,
       },
       
     
   },

   data(){
       return{


       }
       
   },
  
   methods:{

     
   },
  
       
}
 </script>

 <style scoped>
 /* Add any additional styling here */
 </style>
