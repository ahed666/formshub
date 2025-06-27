<template>
    <div>
        <div v-if="form.questions.length > 0">
            <div class="flex justify-center items-center space-x-4 rtl:space-x-reverse" >
                
                <QuestionsPagination :form="form" :currentQuestion="currentQuestion" @set="setCurrentQuestion" />

            </div>

            <QuestionText class="my-4" :questionText="getQuestionText(currentQuestion)" />

            <div
                class=" md:w-2/3 xs:w-full mt-2 flex md:flex-nowrap xs:flex-wrap md:space-y-0
                 md:space-x-8 xs:space-x-0 rtl:space-x-reverse xs:space-y-4">
                <QuestionStatisticsInfo class="md:order-1 xs:order-2 bg-white p-1 rounded-lg" :answersOnResponse="totalAnswers-totalSkips"
                    :skipsCount="totalSkips" :createdDate="currentQuestion.created_at" />
                <DateFilter class="md:order-2 xs:order-1 bg-white p-1 rounded-lg" :startDate="startDate" :endDate="endDate" :minDate="minDate"
                    :maxDate="maxDate" @setStartDate="setStartDate" @setEndDate="setEndDate"
                    @applyDateFilter="applyDateFilter" @export="exportQuestion()" />
            </div>

            <div v-if="checkTextQuestions() === false"
                class="h-auto w-full mt-8  flex md:flex-nowrap flex-wrap md:space-y-0
                 md:space-x-8 xs:space-x-0 rtl:space-x-reverse xs:space-y-4">
               
                <AnswersTable :answers="allAnswers" />
                <Chart 
                :chartHeight="'md:h-[350px] xs:h-[350px] '"
                :radius="'50%'"
                :center="['50%', '40%']"
                class=" bg-white md:w-1/3 xs:w-full"
                v-if="legend.length > 0" :legend="legend" :data="chartData" />

            </div>

            <div v-else
                class="h-auto w-full mt-4 flex md:flex-nowrap flex-wrap justify-start items-center md:space-y-0 md:space-x-8 xs:space-x-0 rtl:space-x-reverse xs:space-y-4">
                <AnswersTextTable :questionType="currentQuestion.type" :answers="allTextAnswers" :currentLang="currentLang" />
            </div>
        </div>
    </div>
    <Loader v-if="loading" />

</template>

<script>
import DateFilter from './components/DateFilter.vue';
import QuestionsPagination from './components/QuestionsPagination.vue';
import QuestionStatisticsInfo from './components/QuestionStatisticsInfo.vue';
import QuestionText from './components/QuestionText.vue';
import dayjs from 'dayjs';
import utc from 'dayjs/plugin/utc';
import timezone from 'dayjs/plugin/timezone';
import AnswersTable from './components/AnswersTable.vue';
import Chart from './components/Chart.vue';
import { Legend } from 'chart.js';
import AnswersTextTable from './components/AnswersTextTable.vue';
import Loader from '../../svgs/Loader.vue';
import { MessageMixin } from '../../mixins/MessageMixin';
import { AnswerMixin } from '../../mixins/AnswerMixin';
import { StatisticsMixin } from '../../mixins/StatisticsMixin';



// Extend dayjs with the plugins
dayjs.extend(utc);
dayjs.extend(timezone);

export default {
    mixins: [MessageMixin,StatisticsMixin,AnswerMixin], 

    components: {
        Loader,QuestionsPagination, QuestionText, QuestionStatisticsInfo, DateFilter, AnswersTable, Chart, AnswersTextTable
    },

    data() {
        return {

            currentQuestion: this.form.questions[0],
            allAnswersWithSums: {},
            allTextAnswers: {},
            filteredResponses: [],
            totalAnswers: 0,
            totalSkips: 0,
            currentLang: 'lang1',
            minDate: null,
            maxDate: null,
            startDate: null,
            endDate: null,
            chartData: [],
            legend: [],
            allAnswers:[],
            loading:false,
        }
    },

    props: {
        form: {
            type: Object,

        },
        responses: {
            type: Array,
        },
    },

    mounted() {
        this.detectedStartAndEndDateForResponses();
        this.initialData();
    },

    methods: {

        // export question 
       async exportQuestion(){
            this.loading = true;
            
            try {
          const response = await axios.post('/statistics/question/export',{
            
            data:this.getExportData(),
          },{
            responseType: 'blob', // This is crucial to handle binary data
        });
         
          if (response.status === 200) {
           
           this.downloadFile(response.data,'question');
          
        }
        } catch (error) {
            
          if (error.response && error.response.data) {
            error.response.data.message?this.showAlert('error',error.response.data.message) : this.showAlert('error','An error occurred.');
        } else {
          this.showAlert('error','An unexpected error occurred.');
          
        }
        }finally{
          this.loading = false;
        }
        },

        

        getExportData(){
            if(this.checkTextQuestions() === false)
            return {'textQuestion':false,'question':this.getQuestionText(this.currentQuestion),'statisticsData':this.allAnswers};
            else
            return {'textQuestion':true,'question':this.getQuestionText(this.currentQuestion),'statisticsData':this.allTextAnswers};


        },

        // get current question text
        getQuestionText(question) {

            var translation = question.translations.filter(trnas => trnas.language == this.currentLang)[0];
            return translation.question_text;

        },



        // set current question(initial data for question text)
        setCurrentQuestion(question) {

            
            this.currentQuestion = question;
            this.initialData();
        },

        checkTextQuestions() {
           
            return [4, 5, 6].includes(this.currentQuestion.type.category_id);
        },


       async initialData() {
            

            this.filteredResponses=await  this.filterResponsesForCurrentQuestion(this.currentQuestion,this.startDate,this.endDate);
            this.calculateSkips();
            this.calculateAnswersOnResponse();
            this.checkTextQuestions() ? this.allTextAnswers=this.textAnswersWithPercent(this.filteredResponses) : this.nonTextAnswersWithPercent();
        },

        formatDateInTimezone(dateString, timezone = 'Asia/Dubai') {
            var formattedDate = dayjs(dateString).tz(timezone).format('ddd MMM DD YYYY HH:mm:ss [GMT]Z (z)');
            return formattedDate
        },

        // set start and end date;
        setStartDate(date) {

            this.startDate = date;

        },

        setEndDate(date) {
            this.endDate = date;

        },
        applyDateFilter() {
            this.initialData();
        },


        // detected start and end dates for responses
        detectedStartAndEndDateForResponses() {
            const dates = this.responses.map(response => response.created_at.split(" ")[0]);
            dates.sort();
            this.startDate = this.minDate = dates[0];          // The earliest date
            this.endDate = this.maxDate = dates[dates.length - 1];
            
        },


        // filterd responses for current question
        async  filterResponsesForCurrentQuestion(question,start,end) {
            // Format the start and end dates to 'YYYY-MM-DD'
         
                console.log('filterResponsesForCurrentQuestion:',question,this.responses,start,end);
            let fResponses=[];
             fResponses = this.responses.flatMap(response => {
                // Format each response date to 'YYYY-MM-DD'
                
                const responseDate = response.created_at.split(" ")[0];
                console.log('response data:',responseDate);
                return response.question_responses.map(qr => {
                    // Only include if question_id matches and within the date range
                    console.log('inside:',qr.question_id , question.id);
                    if (qr.question_id === question.id &&
                        responseDate >= start && responseDate <= end) {
                            
                        // Set created_at of question_response to match the parent's created_at
                        qr.created_at = response.created_at; // Copy created_at from response
                        return qr; // Return the modified question response
                    }
                    return null; // Return null if it does not match, will be filtered out later
                }).filter(qr => qr !== null); // Filter out null values
            });
            console.log('fResponses:',fResponses);
            return fResponses;


        },



        // calculate num of skips 
        calculateSkips() {
            this.totalSkips = 0;
            this.filteredResponses.forEach(questionResponse => {
                if (questionResponse.skip) {
                    this.totalSkips++;
                }
            });
        },

        // calculate num of answers on this response for currentquestion
        calculateAnswersOnResponse() {
            
            this.totalAnswers = Object.keys(
                this.filteredResponses.reduce((acc, response) => {
                    const key = `${response.question_id}-${response.response_id}`;
                    acc[key] = true; // Store the key to ensure uniqueness
                    return acc;
                }, {})
            ).length;


        },

        // get all answers for question of Non text type with it perecent
       async nonTextAnswersWithPercent(){
            this.allAnswersWithSums=await this.calcAnswersWithSums();
            console.log('non text answers function',this.allAnswersWithSums,this.currentQuestion.answers);
            this.allAnswers=await this.calAllanswers(this.allAnswersWithSums,this.currentQuestion.answers);
            this.calculateChartData();
        },

       async calcAnswersWithSums() {
            let  answersWithSums = [];
            // Count occurrences of each answer ID
            
                       console.log('calcAnswersWithSums:',this.filteredResponses);

            this.filteredResponses.forEach(({ answer_id }) => {
                if (answer_id) {
                    
                    answersWithSums[answer_id] =
                        (answersWithSums[answer_id] || 0) + 1;
                }
            });

            return answersWithSums;

            

        },

        // initial answers pre difened for view it in table
        async calAllanswers(answersWithSums,questionAnswers){
            var total=answersWithSums.reduce((sum,item)=>{
                return sum+=item;
            },0);
          var answers=[];
           questionAnswers.forEach(answer => {
                answers.push({
                    text: this.getAnswerText(answer),
                    total: answersWithSums[answer.id]?answersWithSums[answer.id]:0,
                    percent:this.calculatePercent(answersWithSums[answer.id],total),
                });
                
            });
            console.log('answers:',answers,questionAnswers);
            return answers;
        },

        // initial data for chart 
        calculateChartData() {
            this.legend = [];
            this.chartData = [];
            this.currentQuestion.answers.forEach((answer, index) => {
                this.chartData.push({
                    value: this.allAnswersWithSums[answer.id] ? this.allAnswersWithSums[answer.id] : 0,
                    name: index + 1,
                });
            });

            this.legend = this.chartData.map(item => item.name);
            

        },

        // get all answers for question of text type with it perecent
        textAnswersWithPercent(fResponses) {
                                   console.log('textAnswersWithPercent:',fResponses);

           let Answers = [];
            fResponses.forEach((response, index) => {
               Answers.push({
                    text: response.text_response,
                    date: response.created_at,
                    skip: response.skip,
                    textType: this.currentQuestion.type.type_text,
                });
            });
            return Answers;

        }


    }

}
</script>

<style lang="scss" scoped></style>