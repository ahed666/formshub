<!-- ModalTemplate.vue -->
<template>
   
    <div class=" grid grid-cols-10 gap-2 justify-between space-x-2 rtl:space-x-reverse items-center ">
       <div v-for="(answer,index) in Answers" class="2xl:cols-span-2 lg:col-span-2 md:col-span-2 xs:col-span-10">
             
            <div class="grid justify-center items-center border p-1   rounded-lg" v-if="questionType.type_text=='satisfaction'">
                 <div class="flex justify-center items-center mb-1">
                    <img class="object-contain w-12 h-12" :src="answer.image" alt="">

                 </div>
                    <input class="max-w-28 text-sm rounded-lg p-1"  @change="changeAnswerText(index,$event)" type="text" name=""
                :value="answer.text[currentLang]" :id="answer.id">
            </div>

            <div class="grid justify-center items-center border p-1 min-h-28 min-w-28 min-w-28 w-28" v-else-if="questionType.type_text=='rating'">
                 
                <span class="text-3xl">{{answer.text[currentLang]}}</span>


            </div>
             
             
     
             
              
             
   </div>
    </div>
   
   
 </template>

 <script>


 export default {
   components:{
       
   },
   props: {
       answers:{
           type:Array,
           required:false,
       },
       currentLang:{
           type:String,
           required:false,
           default:'lang1',
       },
       questionType:{
         type:Object,
         required:false,
       },
       
     
   },

   data(){
       return{


           
           
           Answers:[],
           

           // defult values for answers 
           predefinedAnswers: {
           'satisfaction': [
           { id: '0', text: { lang1: 'Very Unsatisfied', lang2: 'Very Unsatisfied' }, image: '/images/icons/emoji/1.png' },
           { id: '1', text: { lang1: 'Unsatisfied', lang2: 'Unsatisfied' }, image: '/images/icons/emoji/2.png' },
           { id: '2', text: { lang1: 'Natural', lang2: 'Natural' }, image: '/images/icons/emoji/3.png' },
           { id: '3', text: { lang1: 'Satisfied', lang2: 'Satisfied' }, image: '/images/icons/emoji/4.png' },
           { id: '4', text: { lang1: 'Very Satisfied', lang2: 'Very Satisfied' }, image: '/images/icons/emoji/5.png' }
           ],
           'rating':[
           { id: '0', text: { lang1: '1', lang2: '1' }, image: null },
           { id: '1', text: { lang1: '2', lang2: '2' }, image: null },
           { id: '2', text: { lang1: '3', lang2: '3' }, image: null },
           { id: '3', text: { lang1: '4', lang2: '4' }, image: null },
           { id: '4', text: { lang1: '5', lang2: '5' }, image: null }
        //    { id: '5', text: { lang1: '6', lang2: '6' }, image: null },
        //    { id: '6', text: { lang1: '7', lang2: '7' }, image: null },
        //    { id: '7', text: { lang1: '8', lang2: '8' }, image: null },
        //    { id: '8', text: { lang1: '9', lang2: '9' }, image: null },
        //    { id: '9', text: { lang1: '10', lang2: '10' }, image: null }
           ]
           },
       }
       
   },
   watch: {
       answers: {
           immediate: true, // Trigger immediately
           handler(newVal) {
                  // If newVal is not empty, use it; otherwise, use predefined answers
               this.Answers = newVal && newVal.length > 0 ? newVal : this.predefinedAnswers[this.questionType.type_text];
               console.log(this.Answers,newVal );
               this.$emit('updateAnswers', this.Answers); // Emit updated answers to parent
           },
       },
       
   },
   methods:{

       changeAnswerText(index,event){
         this.Answers[index].text[this.currentLang]=event.target.value;
          this.$emit('updateAnswers',this.Answers);



       },
       // open or close dropdown menu of answer images
       toggleDropdown(index) {
     // Toggle the visibility of the dropdown for the current answer
     this.dropdownVisible[index]=!this.dropdownVisible[index];
   },


   // when user select image from dropdown menu
   selectImage(selectedImage, index) {
     // Update the answer's image with the selected option
     this.Answers[index].image = selectedImage;
     // Close the dropdown after selecting an image
     this.dropdownVisible[index]=false;
     
     this.$emit('updateAnswers',this.Answers);
   }
   },
  
       
}
 </script>

 <style scoped>
 /* Add any additional styling here */
 </style>
