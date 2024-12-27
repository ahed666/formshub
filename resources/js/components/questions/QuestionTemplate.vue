<!-- ModalTemplate.vue -->
<template>

    <!-- if edit question -->
    <div v-if="question" class="grid justify-center">
          
         
        <QuestionText :questionText="currentQuestion.questionText[currentLang]" @updateQuestionText="updateQuestionText"> </QuestionText>
        <AnswerTemplate @updateAnswers="updateAnswers" :currentLang="currentLang" :answers="currentQuestion.answers"  :questionType="currentQuestion.questionType"></AnswerTemplate>
        
    </div>

    <!-- if add question -->
    <div v-else class="grid justify-center">
          
         
          <QuestionText  @updateQuestionText="updateQuestionText"> </QuestionText>
          <AnswerTemplate @updateAnswers="updateAnswers"  :questionType="selectedQuestionType"></AnswerTemplate>
          
      </div>

    
  </template>

  <script>
 import QuestionText from './QuestionText.vue';
 import AnswerTemplate from './answers/AnswerTemplate.vue';
  export default {
    components:{
        QuestionText,
        AnswerTemplate,
    },
    props: {
        selectedQuestionType:{
        type:Object,
        Required:false,

    },
    question:{
        type:Object,
        Required:false,
    },
    language:{
        type:String,
        Required:false,
    },
   
      
    },
    data(){
        return {
            currentQuestion:null,
            currentLang:null,

        }
    },
    watch: {
        question: {
            
            immediate: true, // Trigger immediately
            handler(newVal) {
               
                this.currentQuestion = newVal; // Set currentQuestion when question prop changes
            },
        },
        language:{
            immediate:true,
            handler(newVal){
            this.currentLang=newVal;
            }
        }
    },
    methods:{

        // when typing question text
        updateQuestionText(question_text){
              this.$emit('updateQuestionText',question_text);
        },
        // when make any edit pn answers 
        updateAnswers(answers){
              this.$emit('updateAnswers',answers);
        }
        
    }
  };
  </script>

  <style scoped>
  /* Add any additional styling here */
  </style>
