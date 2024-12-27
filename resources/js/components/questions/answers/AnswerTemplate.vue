<!-- ModalTemplate.vue -->
<template>

    <div class="grid  justify-center">
        <div class="mt-2 flex justify-center items-center space-x-1 rtl:space-x-reverse px-4">
            <span class="text-sm text-secondary_red font-bold"> {{ translations.forms.answers_title }} </span>
            <span class="text-sm">{{AnswersTextQuestion()?translations.forms.answers_text_title:translations.forms.answers_text_title}} </span>
        </div>
        <div class="w-full">
            <!-- logic answers template -->
      <LogicAnswersTypeTemplate :currentLang="currentLang" :answers="Answers" @updateAnswers="updateAnswers" v-if="logicQuestion()" ></LogicAnswersTypeTemplate>
      <MultiAnswersTypeTemplate :currentLang="currentLang" :answers="Answers" @updateAnswers="updateAnswers" v-if="multiAnswersQuestion()" ></MultiAnswersTypeTemplate>
      <RatingSatisfactionTypeTemplate :questionType="questionType" :currentLang="currentLang" :answers="Answers" @updateAnswers="updateAnswers" v-if="RatingSatisfactionQuestion()" ></RatingSatisfactionTypeTemplate>
      <TextAnswersTypeTemplate :questionType="questionType" v-if="AnswersTextQuestion()"   />
       </div>
    </div>
    
  </template>

  <script>
 

import LogicAnswersTypeTemplate from './LogicAnswersTypeTemplate.vue';
import MultiAnswersTypeTemplate from './MultiAnswersTypeTemplate.vue';
import RatingSatisfactionTypeTemplate from './RatingSatisfactionTypeTemplate.vue';
import TextAnswersTypeTemplate from './TextAnswersTypeTemplate.vue';
  export default {

    components:{
        LogicAnswersTypeTemplate,
        MultiAnswersTypeTemplate,
        RatingSatisfactionTypeTemplate,
        TextAnswersTypeTemplate,
    },
    data(){
        return{
         Answers:null,
         translations:window.translations,
        }
    },
    props: {
        questionType:{
            type:Object,
            Required:true,
        },
        answers:{
            type:Object,
            Required:false,
        },
        currentLang:{
            type:Object,
            Required:false,
        }
      
    },
    watch: {
        answers: {
            immediate: true, // Trigger immediately
            handler(newVal) {
                   // If newVal is not empty, use it; otherwise, use predefined answers
                this.Answers = newVal ;
            },
        },
        
    },
    methods:{

        //checl if this question is text answers question
        AnswersTextQuestion(){
            return [4, 5, 6].includes(this.questionType.category_id);

        },

        // check if this question is logic question
        logicQuestion(){
            return this.questionType.type_text=='logic_question';
        },
        
        // check if this question is rating or satisfaction question
        RatingSatisfactionQuestion(){
            return this.questionType.category_id==3;

        },
        // check if this question is multi answers question
        multiAnswersQuestion(){
            //   check dependent on category of question
              return this.questionType.category_id==2;
        },

        updateAnswers(answers){
            this.$emit('updateAnswers',answers);

        }
        
    }
  };
  </script>

  <style scoped>
  /* Add any additional styling here */
  </style>
