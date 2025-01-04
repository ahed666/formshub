<template>
    <div  class=" grid grid-cols-3  w-full mt-4  gap-2 md:p-0 xs:p-2 ">
        <button 
            @click="backQuestion"
            class="md:text-3xl xs:text-xl font-bold uppercase col-span-1 h-20 bg-gray-400 hover:bg-gray-400 focus:bg-gray-400 w-full text-white select-none opacity-100">
            <span class="md:text-4xl xs:text-xl mr-4">↶</span>Back
        </button>


        <button @click="skipQuestion"  v-if="questionOptional&&!allowNext"
            class="flex justify-center items-center md:text-3xl xs:text-xl font-bold uppercase   col-span-2 h-20  disabled:bg-blue-200 bg-secondary_blue w-full text-white select-none">
            Skip<span class="md:text-4xl xs:text-xl ml-4"> ↷</span>
        </button>

        <button  @click="nextQuestion"
          v-else-if="!questionOptional||allowNext" :disabled="!allowNext"
            class="flex justify-center items-center md:text-3xl xs:text-xl font-bold uppercase  
             col-span-2 h-20  disabled:bg-blue-200 bg-secondary_blue w-full text-white select-none">
            Next <span :class=" ['md:text-4xl xs:text-xl ml-8',{'animate-ping':allowNext}]">➝</span>
        </button>
    </div>
</template>

<script>
export default {

    props:{
        questionOptional:{
            type:Boolean,
        },
        allowNext:{
            type:Boolean,
        },
    },
    methods:{
        skipQuestion(){
               this.$emit('skip');
        },
        nextQuestion(){
            this.$emit('next');
        },
        backQuestion(){
            this.$emit('back');
        }
    },

}
</script>

<style lang="scss" scoped></style>