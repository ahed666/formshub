<template>
    <div>
        <!-- statistic info  -->
        <div>
            <FormStatisticsInfo :responses="responses" :createdDate="form.created_at" />
        </div>
        <div
            class="h-auto w-full mt-8 flex md:flex-nowrap flex-wrap md:space-y-0 md:space-x-8 xs:space-x-0 rtl:space-x-reverse xs:space-y-4">
            
            <Chart class="md:w-[40%] xs:w-full" v-if="legend.length > 0" :legend="legend" :data="chartDataResponsesPerDevices" />

            <LatestResponsesTable class="md:w-[60%] xs:w-full" :questionsWithAnswers="form.questions" :responses="latestResponses" />
        </div>


    </div>
</template>

<script>
import FormStatisticsInfo from './components/FormStatisticsInfo.vue';
import Chart from './components/Chart.vue';
import LatestResponsesTable from './components/LatestResponsesTable.vue';

export default {

    components: {
        FormStatisticsInfo,Chart,LatestResponsesTable
    },

    data(){
        return{

            chartDataResponsesPerDevices:[],
            legend:[],
            translations: window.translations,

        }
    },
    mounted(){
        this.initalData();
    },

    props: {
        form: {
            type: Object,

        },
        responses: {
            type: Array,
        },

        latestResponses: {
            type: Array,
        },
    },

    methods:{

        initalData(){
            this.calculateChartData();
        },
          // initial data for chart 
          calculateChartData(){
                this.legend=[];
                this.chartData=[];
                var sumsOnDevices = {};

                this.responses.forEach((response, index) => {
                    // Check if the device_id property already exists
                    if (!sumsOnDevices[response.device_id]) {
                        // Initialize the device_id property as an object with value and name
                        sumsOnDevices[response.device_id] = {
                            value: 0,
                            name:response.device_id==null? this.translations.forms.unknown:response.device.name,
                        };
                    }

                    // Increment the value for this device
                    sumsOnDevices[response.device_id].value += 1;
                });
                console.log(sumsOnDevices);
                this.chartDataResponsesPerDevices=Object.values(sumsOnDevices);
                this.legend=this.chartDataResponsesPerDevices.map(item => item.name);



              

            },
    },

}
</script>

<style lang="scss" scoped></style>