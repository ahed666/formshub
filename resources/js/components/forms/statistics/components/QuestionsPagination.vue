<template>
    <div class="flex justify-center items-center space-x-4">
    <!-- Prev Button -->
     
    
    <Prev 
        :disabled="currentIndex === 0" 
        @click="prev"  
        :class="{
            'text-gray-400 cursor-not-allowed': currentIndex === 0,
            'hover:text-blue-500 cursor-pointer': currentIndex !== 0
        }"
    />

    <!-- Page Indicators -->
    <div class="flex justify-center items-center space-x-1 rtl:space-x-reverse max-w-28 overflow-auto ">
        <div 
            v-for="(question, index) in form.questions" 
            @click="set(question, index)" 
            :class="[
                'bg-gray-200 w-6 h-6 hover:text-white hover:bg-secondary_blue hover:opacity-75 rounded-full text-center hover:shadow-lg hover:cursor-pointer', 
                {'bg-secondary_blue text-white': currentQuestion.id === question.id}
            ]"  
            :key="'pag-question-' + question.id"
        >
            {{ index + 1 }}
        </div>
    </div>

    <!-- Next Button -->
    <Next 
        :disabled="currentIndex + 1 >= form.questions.length" 
        @click="next"  
        :class="{
            'text-gray-400 cursor-not-allowed': currentIndex + 1 >= form.questions.length,
            'hover:text-blue-500 cursor-pointer': currentIndex + 1 < form.questions.length
        }"
    />
</div>

    
</template>

<script>
import Prev from '../../../svgs/Prev.vue';
import Next from '../../../svgs/Next.vue';
    export default {
        components:{
            Prev,
            Next,
        },

        data(){

            return{
                currentIndex:0,
            }

        },
        props:{
            form:{
                type:Object,
            },
            currentQuestion:{
                type:Object,
            },
        },

        methods:{
            set(question,index=this.currentIndex){
                console.log(index);
                this.currentIndex=index;
                this.$emit('set',question);
            },
            next(){
               
                if(this.currentIndex+1<this.form.questions.length)
                {this.currentIndex+=1;
                this.set(this.form.questions[this.currentIndex]);}
                return ;
            },
            prev(){
                if(this.currentIndex>0)
                {this.currentIndex-=1;
                this.set(this.form.questions[this.currentIndex]);}
                return;
            }

        },
    }
</script>

<style lang="scss" scoped>

</style>