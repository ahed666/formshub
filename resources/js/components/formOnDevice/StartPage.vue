<template>

    <div class="grid justify-center items-center p-14"v-if="formTranslations!=null">
        
        <div class="flex justify-center items-center mb-8"><img class="h-auto w-48" :src="logo" alt=""></div>
        <vue-carousel :data="formTranslationsData" :indicators="false" :controls="false"></vue-carousel>
        
         <div class="md:flex xs:grid justify-center items-center gap-4 mt-20">
            <button @click="startForm(translation)" class="border-gray-300 bg-[#F9FAFB] hover:bg-gray-100 border-[2px] rounded-lg text-2xl w-48 max-w-[48] min-w-[48] h-24 font-bold" v-for="(translation,index) in formTranslations">
                  {{ translation.language_name }}
            </button>

         </div>
       
    </div>
</template>

<script>
// import { Carousel, Slide } from 'vue-carousel';
// import {carousel,slide} from '@chenfengyuan/vue-carousel'
import VueCarousel from '@chenfengyuan/vue-carousel';


    export default {

        components:{
            VueCarousel,
        },

        props:{
            formTranslations:{
                type:Object,
                Required:false,
            },
            logo:{
                type:String,
                Required:false,
            },
        },
        watch: {
        formTranslations: {
            immediate: true,
            handler(newTranslations) {
                if (newTranslations) {
                    this.generateFormTranslationsData(newTranslations);
                }
            },
        },
    },
    data() {
        return {
            formTranslationsData: [], // Holds transformed data
        };
    },
    methods:{
        // start form
        startForm(translation){
            console.log(translation);
          this.$emit('startform',translation);
        },

        // generate form transllation data for carousel
        generateFormTranslationsData(translations) {
            // Clear previous data
            this.formTranslationsData = [];

            // Loop through translations and construct divs
            for (const translation of Object.values(translations)) {
                const content = `
                    <div class="grid justify-center items-center">
                        <h1 class="font-bold text-5xl leading-tight select-none mb-5 text-center">${translation.start_header}</h1>
                        <h2 class="font-weight-bolder leading-tight text-3xl select-none text-center">${translation.start_message}</h2>
                    </div>`;
                this.formTranslationsData.push(content);
            }
        },
    },
        
    }
</script>

<style lang="scss" scoped>

</style>