<template>
     <div class=" md:w-1/2 xs:w-full bg-white rounded-lg p-2 ">
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
                if (!this.responses.length) return '0.00';

                const total = this.responses.reduce((sum, response) => {
                    return sum + parseFloat(response.completion_avg || 0);
                }, 0);

                return (total / this.responses.length).toFixed(2);
            }
        },
        
    }
</script>

<style lang="scss" scoped>

</style>