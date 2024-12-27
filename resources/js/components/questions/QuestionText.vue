<!-- ModalTemplate.vue -->
<template>

    <div class="grid justify-center">
        <div class="flex justify-center space-x-6 rtl:space-x-reverse items-center  text-sm mb-1 w-full h-[10px] max-h-[10px] min-h-[10px] ">
        <div>
            <span class="text-sm text-secondary_red font-bold">{{ translations.forms.add_question_title }}</span>
            <span class="text-sm">{{ translations.forms.add_question_desc }} </span>
        </div>
        <span><span id="question_counter">{{  currentQuestionText?currentQuestionText.length:0 }}</span>/255</span>
         </div>
         <div class="">
           
        <textarea  @input="updateQuestionText($event)"  v-model="currentQuestionText"  maxlength="255" rows="3" cols="90" 
            class="text-2xl xs:text-sm text-center w-full  resize-none focus:otline-none
            border-[1px] w-full overflow-y-none p-[1px] rounded-lg border-gray-200"
            required autofocus min="10" minlength="10" 
            pattern=".*\S+.*" title="Input cannot contain only spaces." 
            id="questionText">
        </textarea>
        </div>

    </div>
    
  </template>

  <script>
 
  export default {
    components:{
           
    },
    data() {
    return {
      currentQuestionText: '',  // Variable to store the text entered in the textarea
      translations:window.translations,

    }},
    props: {
      questionText:{
          type:String,
          required:false,
          default:"",
      },
    },
    watch: {
    questionText:{
        immediate: true, // Trigger immediately
            handler(newVal) {
                console.log('new:',newVal);
                this.currentQuestionText = newVal || ''; // Set currentQuestionText to new value or empty string
            },
    },
    },
    methods:{
        updateQuestionText(event){
             this.currentQuestionText=event.target.value;
            this.$emit('updateQuestionText',this.currentQuestionText);
        }
    }
  };
  </script>

  <style scoped>
  /* Add any additional styling here */
  </style>
