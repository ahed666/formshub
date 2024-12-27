<template>
    <div class="grid 2xl:w-1/2 xl:w-1/2 lg:w-1/2 md:w-1/2 xs:w-full gap-2 p-2">
        <div class="grid gap-2">
            <div class="grid  items-center  ">
                <label for="start">From:</label>
                <input class="rounded-lg border-gray-200 p-2 max-w-[80%] w-full text-center"
                 v-model="currentSrartDate" 
                @input="setStartDate($event)"  :min="minDate"  :max="maxDate" type="date" 
                id="start" name="start" :value="startDate" required>
            </div>
            <div class="grid  items-center ">
                <label for="end">To:</label>
                <input class="rounded-lg border-gray-200 p-2 w-full max-w-[80%] text-center" 
                v-model="currentEndDate"
                @input="setEndDate($event)" :min="startDate" :max="maxDate"  type="date" id="end" name="end" :value="endDate" required>
            </div>
           

            
        </div>
        <div class="flex justify-center items-center space-x-4 rtl:space-x-reverse">
            <PrimaryButton :title="'Apply'" @onClick="applyDateFilter()" />
            <PrimaryButton :title="'Export'" @onClick="exportQuestion()" />

            
        </div>
    </div>
</template>

<script>
import PrimaryButton from '../../../actions/PrimaryButton.vue';

    export default {
        components:{PrimaryButton,},
        data(){
            return{
                currentSrartDate:this.startDate,
                currentEndDate:this.endDate,
            }
        },

        props:{
            startDate:{
                type:Date,
            },
            endDate:{
                type:Date
            },
            minDate:{
                type:Date
            },
            maxDate:{
                type:Date
            },
        },

        data(){
            return {

            }
        },

        methods:{
            setStartDate(event){

                this.$emit('setStartDate',event.target.value);
                
                console.log('to',event.target.value);
            },
            setEndDate(event){
                this.$emit('setEndDate',event.target.value);
                console.log('from',event.target.value);

            },
            applyDateFilter(){
                this.$emit('applyDateFilter');
            },
            exportQuestion(){
                this.$emit('export');

            }
            
        },
        
    }
</script>

<style lang="scss" scoped>

</style>