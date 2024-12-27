<template>
<div class="grid grid-cols-12 gap-4 m-4 w-full ">
    <div v-for="(answer, index) in answers" :key="answer.id" 
    :class="['flex justify-center items-center',{'col-span-4':answersViewMode==null,'col-span-12':answersViewMode==='1_column',
    'col-span-6':answersViewMode==='2_columns','col-span-4':answersViewMode==='3_columns'}]">
        <input :type="getSelectionType() === 'single' ? 'radio' : 'checkbox'" :name="'answers-question-' + questionId"
            :id="'answer-' + answer.id" @change="chooseAnswer(answer, true, getSelectionType())"
            class="hidden peer pointer-events-none" />
        <label class=" border-gray-300 peer-checked:drop-shadow-lg peer-checked:bg-blue-100
         peer-checked:text-blue-600 peer-checked:font-bold peer-checked:text-xl
         
          max-w-96 w-full  h-24
                                        flex justify-center items-center p-2   text-gray-500 bg-formbutton border-[2px] rounded-lg cursor-pointer
                                     peer-checked:border-blue-400
                                    hover:text-gray-600 hover:bg-gray-100
        " :for="'answer-' + answer.id">
            <h1>{{ getCurrentAnswerText(answer) }}</h1>
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
        questionId: {
            type: String,

        },
        questionsWithAnswers: { // Receive questionsWithAnswers as a prop
            type: Object,
            required: true,
        },

        questionType: {
            type: Object,
        },
        answersViewMode:{
            type:String,
            default:null,
            required:false,
        }


    },
    data() {
        return {}
    },
    methods: {
      
       
    },
}
</script>

<style lang="scss" scoped></style>