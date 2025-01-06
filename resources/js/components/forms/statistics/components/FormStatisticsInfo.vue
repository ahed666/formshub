<template>
     <div class=" md:w-1/2 xs:w-full bg-white p-2 ">
        <InfoLine :title="translations.forms.answers" :count="responses.length" :withBottomBorder="true" />
        <InfoLine :title="translations.forms.completion_avg" :count="calculateCompletionAvg()" :unit="'%'"  :withProgressBar="true" :withBottomBorder="true" />
        <InfoLine :title="translations.forms.age" :count="calculateAge(createdDate)"  />

    </div>
</template>

<script>
import InfoLine from './InfoLine.vue';
import { StatisticsMixin } from '../../../mixins/StatisticsMixin';
    export default {
        
        mixins: [StatisticsMixin], // Use the mixin in the parent component

        components:{
            InfoLine,
        },
        data(){
            return{
                translations: window.translations,

            }
        },
        props:{

            

            responses:{
                type:Array,
            },

            createdDate:{
                type:Date,
            },

        },
        methods:{
            calculateCompletionAvg(){
                var CompletionAvg=this.responses.reduce((sumAvg,response)=>{
                    return sumAvg+=response.completion_avg;
                },0);

                return (CompletionAvg/this.responses.length).toFixed(2);
            }
        },
        
    }
</script>

<style lang="scss" scoped>

</style>