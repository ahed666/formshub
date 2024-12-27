<template>
<div class="grid grid-cols-12 gap-4 ">
    <div v-for="(answer, index) in answers" :key="answer.id" class="col-span-6">
        <input type="radio"
        :name="'answers-question-'+questionId"
        :id="'answer-'+answer.id" @change="chooseAnswer(answer, true,'single')"
            class="hidden peer pointer-events-none"  />
        <label
        class=" border-gray-300 peer-checked:drop-shadow-lg peer-checked:bg-blue-100 peer-checked:text-blue-600 peer-checked:font-bold peer-checked:text-xl w-48 max-w-[48] min-w-[48] h-24
                                        flex justify-center items-center p-2   text-gray-500 bg-formbutton border-[2px] rounded-lg cursor-pointer
                                     peer-checked:border-blue-400
                                    hover:text-gray-600 hover:bg-gray-100
        "
         :for="'answer-'+answer.id">
            <h1>{{ getCurrentAnswerText(answer) }}</h1>
            <img v-if="answer.image != null" class="rounded-full object-contain w-14 ml-1 mr-1" :src="answer.image"
                alt="">
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


    },
    data() {
        return {}
    },
    methods: {
        
    },
}
</script>

<style lang="scss" scoped></style>