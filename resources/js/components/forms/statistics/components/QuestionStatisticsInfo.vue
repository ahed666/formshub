<template>
    <div class=" md:w-1/2 xs:w-full">
        <InfoLine :title="translations.forms.answers" :count="answersOnResponse" :withBottomBorder="true" />
        <InfoLine :title="translations.forms.skips" :count="skipsCount" :withBottomBorder="true" />
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

            answersOnResponse:{
                type:Number,
            },

            skipsCount:{
                type:Number,
            },

            createdDate:{
                type:Date,
            },

        },

        methods:{
        
        },
    }
</script>

<style lang="scss" scoped>

</style>