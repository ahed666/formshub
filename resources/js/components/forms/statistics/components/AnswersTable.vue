<template>
    <div class="max-h-full  md:w-2/3 xs:w-full bg-white p-1  rounded-lg">

        <table class="min-w-full border border-gray-200">
            <thead class="bg-gray-100 sticky top-0 z-10"> <!-- Keep header fixed -->
            <tr>
                <th class="py-2 px-4 border-b font-semibold w-1/2 text-center">{{ translations.forms.answer }}</th>
                <th class="py-2 px-4 border-b font-semibold w-1/4 text-center">{{ translations.forms.qty }}</th>
                <th class="py-2 px-4 border-b font-semibold w-1/4 text-center">{{ translations.forms.percent }}</th>
            </tr>
            </thead>
        </table>
            <div class="overflow-y-auto" style="max-height: 150px;"> <!-- Set your max-height here -->
            <table class="min-w-full border-t border-gray-200">
            <tbody>
                <tr v-for="(answer,index) in answers" :key="index" class="hover:bg-gray-50">
          <td class="py-2 px-4 border-b w-1/2 text-left">{{ index+1}}.{{ answer.text }}</td>
          <td class="py-2 px-4 border-b w-1/4 text-center">{{ answer.total }}</td>
          <td class="py-2 px-4 border-b w-1/4 text-center">{{ answer.percent }}%</td>
        </tr>
                
            </tbody>
            </table>
        </div>
     

    </div>
</template>

<script>
    export default {
        props:{

            answers:{
                type:Object,

            },
            // currentLang:{
            //     type:String,
            // },

            // allAnswersWithSums:{
            //     type:Array,

            // },

        },
        components:{

        },
        data(){
            return{
                totalAnswers:0,
                translations: window.translations,

            }
        },
        mounted(){
            // this.allAnswersWithSums.length>0?this.calculateSumAnswers():'';

        },
        watch:{

        //     allAnswersWithSums:{
        //     handler(newVal) {
        //        newVal.length>0?this.calculateSumAnswers():'';
        //     }
        // }
         
        },

        methods:{
           
        },
    }
</script>

<style lang="scss" scoped>

</style>