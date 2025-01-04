<template>
    <div class="grid grid-cols-10 justify-center items-center gap-4 ">
        <div v-for="(answer, index) in answers" :key="answer.id" class="md:col-span-2 xs:col-span-10">
            <input type="radio"
            :name="'answers-question-'+questionId"
            :id="'answer-'+answer.id" @change="chooseAnswer(answer, true,'single')"
                class="hidden peer pointer-events-none"  />
            <label
            :class="[{'min-w-40':questionType.type_text=='rating'},
             'border-gray-300 peer-checked:drop-shadow-lg peer-checked:bg-blue-100',
              'peer-checked:text-blue-600 peer-checked:font-bold peer-checked:text-xl',
             'w-full   h-auto grid justify-center items-center p-2',
          'text-gray-500 bg-formbutton border-[2px] rounded-lg cursor-pointer',
            'peer-checked:border-blue-400',
          'hover:text-gray-600 hover:bg-gray-100']
            "
             :for="'answer-'+answer.id">
             <div class="flex justify-center items-center">
                <img v-if="answer.image != null" class="object-contain w-16 min-w-16 max-w-16 min-h-16 max-h-16  h-16" :src="answer.image" alt="">
             </div>
            <h1 :class="['text-sm text-center my-2',{'text-xl':questionType.type_text=='rating'}]">{{ getCurrentAnswerText(answer) }}</h1>
                
            </label>
        </div>
        </div>
    </template>
    
    <script>
    import { AnswerMixin } from '../../../mixins/AnswerMixin';

    export default {
        mixins: [AnswerMixin], // Use the mixin in the parent component

        components: {},
        props: {
            answers: {
                type: Object,
                Required: true,
    
            },
            currentTranslation: {
                type: Object,
                Required: true,
            },
            questionId:{
                type:String,
    
            },
            questionsWithAnswers: { // Receive questionsWithAnswers as a prop
                type: Object,
                required: true,
            },
            questionType:{
                type:String,
                Required:true,
            },
    
    
        },
        data() {
            return {}
        },
        methods: {
            
        },
    }
    </script>
    
    <style lang="scss" scoped></style>