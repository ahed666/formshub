<template>
    <div class="h-full flex flex-col  overflow-hidden bg-gray-100">
        <!-- statistic info  'md:space-y-0 md:space-x-8 xs:space-x-0 rtl:space-x-reverse xs:space-y-4'  -->
        <div class=" mb-2">
            <FormStatisticsInfo :responses="responses" :createdDate="form.created_at" />
        </div>
        <div class="grid md:grid-cols-3  gap-2 ">
            <div class=" bg-white rounded-lg shadow p-2 xs:col-span-3 md:col-span-1">
                <h2 class="text-lg font-bold text-center mb-4">{{ translations.dashboard.responsesperdevice_section_title }}</h2>
                <Chart 
                :chartHeight="'md:h-[350px] xs:h-[350px] '"
                :radius="'50%'"
                :center="['50%', '40%']"
                class="  "
                v-if="legend.length > 0" :legend="legend" :data="chartDataResponsesPerDevices" />
            </div>
            <div class="bg-white rounded-lg xs:col-span-3 md:col-span-2 p-2 ">
                <LatestResponsesTable class="  " :questionsWithAnswers="form.questions" :responses="latestResponses" />

            </div>
           
          

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