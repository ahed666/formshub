<!-- ModalTemplate.vue -->
<template>
   
    <div class="grid grid-cols-12 gap-4 justify-center">
       <div :key="index" v-for="(answer,index) in Answers" 
       :class="answer.hide ? 'opacity-50' : 'opacity-100'" 
       class="col-span-6 border flex justify-between space-x-2 rtl:space-x-reverse items-center rounded-lg p-2">
             
             <div class="rounded-full h-6 w-6 bg-secondary_blue text-white text-center ">
                {{ index+1 }}
             </div>
             <input :key="index" @input="changeAnswerText(index,$event)" type="text" name=""
              :value="answer.text[currentLang]" :id="answer.id">

              
            <div class="" >
                 
                <Hide @hide="hideAnswer" :itemId="answer.id" :statusHide="answer.hide" />

            </div>
            <Delete v-if="Answers.length>1" :deleteAction="() => deleteAnswer(answer.id)" :confirmText="'Are you sure you want to delete this answer?'"></Delete>

        </div>

        <!-- add new Answer -->
        <div @click="addAnswer" class="hover:cursor-pointer col-span-6 border flex justify-center space-x-2 rtl:space-x-reverse items-center rounded-lg p-2">
            <svg  class="w-6 h-6  hover:cursor-pointer ease-in delay-100  hover:-translate-z-1 hover:scale-[1.2] duration-200" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g stroke-width="0"></g>
                <g stroke-linecap="round" stroke-linejoin="round"></g>
                <g> <path opacity="0.5" d="M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z" fill="#1277d1"></path> <path d="M12.75 9C12.75 8.58579 12.4142 8.25 12 8.25C11.5858 8.25 11.25 8.58579 11.25 9L11.25 11.25H9C8.58579 11.25 8.25 11.5858 8.25 12C8.25 12.4142 8.58579 12.75 9 12.75H11.25V15C11.25 15.4142 11.5858 15.75 12 15.75C12.4142 15.75 12.75 15.4142 12.75 15L12.75 12.75H15C15.4142 12.75 15.75 12.4142 15.75 12C15.75 11.5858 15.4142 11.25 15 11.25H12.75V9Z" fill="#1277d1"></path> </g>
            </svg>
            {{ translations.buttons.add_answer }}
        </div>
    </div>
   
   
 </template>

 <script>
import Hide from '../../actions/Hide.vue';
import Delete from '../../actions/Delete.vue';
 export default {
   components:{
       Hide,Delete,
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
       
     
   },

   data(){
       return{

          newAnswersCount:0,
          translations:window.translations,
          Answers:[],
       }
       
   },
   watch: {
       answers: {
           immediate: true, // Trigger immediately
           handler(newVal) {
                  // If newVal is not empty, use it; otherwise, use predefined answers
               this.Answers = newVal && newVal.length > 0 ? newVal : [];
               console.log(this.Answers,newVal );
               this.$emit('updateAnswers', this.Answers); // Emit updated answers to parent
           },
       },
       
   },
   methods:{

       // delete answer
       deleteAnswer(id){
        this.Answers = this.Answers.filter(answer => answer.id !== id);
        this.$emit('updateAnswers', this.Answers); // Emit updated answers to parent


       },

        //  hide answer 
        hideAnswer(answerId){
           
            const answer = this.Answers.find(answer => answer.id === answerId);
            answer.hide=!answer.hide;
            this.$emit('updateAnswers', this.Answers);



        },
        // add new answer by create new answer object and add it to answers array
       addAnswer(){
           let newAnswer={id:'new-answer-'+this.newAnswersCount,text:{lang1:'new-answer-'+this.newAnswersCount,lang2:'new-answer-'+this.newAnswersCount},hide:false,image:null};
           this.Answers.push(newAnswer);
           this.newAnswersCount+=1;
           this.$emit('updateAnswers', this.Answers); // Emit updated answers to parent


       },

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
