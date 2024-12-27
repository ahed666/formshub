<template>
    <div class="max-h-full w-full">
        <h1 class="mb-1"> {{ translations.forms.latest_responses_title }}</h1>
    <table class="min-w-full border border-gray-200">
        <thead class="bg-gray-100 sticky top-0 z-10"> <!-- Keep header fixed -->
        <tr>
            <th class="py-2 px-4 border-b font-semibold text-center w-1/4">{{ translations.forms.answer }}</th>
            <th class="py-2 px-4 border-b font-semibold text-center w-1/4">{{ translations.forms.source }}</th>
            <th class="py-2 px-4 border-b font-semibold text-center w-1/4">{{ translations.forms.completion_percent }}</th>
            <th class="py-2 px-4 border-b font-semibold text-center w-1/4">{{ translations.forms.options }}</th>

        </tr>
        </thead>
    </table>
    
    
    <!-- Wrapper for scrollable body -->
    <div v-if="responses.length>0" class="overflow-y-auto" style="max-height: 250px;"> <!-- Set your max-height here -->
        <table class="min-w-full border-t border-gray-200 text-sm">
        <tbody>
            <tr v-for="(response, index) in responses" :key="index" class="border-b hover:bg-gray-50 min-h-14">
            <td class="py-2 px-4  w-1/4 text-xs text-center">{{ formatDateTime(response.created_at) }}</td>
            <td class="py-2 px-4  w-1/4 text-center">{{  response.device_id!=null?response.device.name:translations.forms.unknown }}</td>
            <td class="py-2 px-4   flex justify-between items-center space-x-1 rtl:space-x-reverse">
                <ProgressBar  :percent="formatNumber(response.completion_avg,2)" class="min-w-[75%] max-w-[75%]" />
                <h1>{{ formatNumber(response.completion_avg,2) }}</h1>
            </td>
            <td class="py-2 px-4  w-1/4 ">
                    <ViewSvg class="flex justify-center items-center" @click="showResponse(response)" />
            </td>
            </tr>
        </tbody>
        </table>
    </div>
</div>
    <ViewResponseModal 
    v-if="viewResponse&&responseData!=null&&formQuestions!=null"
    :responseData="responseData"
    :formQuestions="formQuestions"
    @close="closeViewResponseModal"
    />
    

</template>

<script>
import { StatisticsMixin } from '../../../mixins/StatisticsMixin';
import ProgressBar from './ProgressBar.vue';
import CustomTable from './CustomTable.vue';
import ViewSvg from '../../../svgs/View.vue';
import ViewResponseModal from '../../../modals/ViewResponseModal.vue';

    export default {
        mixins: [StatisticsMixin], // Use the mixin in the parent component

        components:{
            CustomTable,ProgressBar,ViewResponseModal,ViewSvg,
        },
        props:{
            responses:{
                type:Array,
            },
            questionsWithAnswers:{
                type:Array,
            },
        },
        mounted(){
        },

        data(){

            return{
                translations: window.translations,
            currentResponseToView:null,
            viewResponse:false,
            responseData:null,
            formQuestions:null,
            
            
            }
        },

        methods: {
           
            async  showResponse(response){
                console.log("Show response start");
                await this.initalViewResponseData(response);
                
                console.log("Show response after initalViewResponseData");
                this.currentResponseToView = response;
                this.viewResponse = true;
                console.log("Show response end");
                
            },
            closeViewResponseModal(){
                this.viewResponse=false;
                this.responseData=null;
            },
           async initalViewResponseData(response){
                this.responseData = response.question_responses.reduce((acc, curr) => {
                if (!acc[curr.question_id]) {
                    acc[curr.question_id] = {
                    question_id: curr.question_id,
                    answers: [],
                    text_response:curr.text_response,
                    skip:curr.skip
                    };
                }
                acc[curr.question_id].answers.push(curr.answer_id);
                return acc;
            }, {});
           this.formQuestions= await this.getQuestionsWithAnswers();
            console.log('response data',this.responseData,this.formQuestions);
            },

          async  getQuestionsWithAnswers(){
                return this.questionsWithAnswers.reduce((acc, question) => {
                acc[question.id] = question;
                return acc;
                }, {});
        },
        },

        
        
    }
</script>

<style lang="scss" scoped>

</style>