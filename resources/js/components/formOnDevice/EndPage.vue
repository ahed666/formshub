<template>
    <div class="grid justify-center items-center p-14"v-if="currentTranslation!=null">
                <div class="flex justify-center items-center mb-8"><img class="h-auto w-36" src="../../../../public/images/deafult/success.svg" alt=""></div>
        <div class="grid justify-center items-center">
                        <h1 class="font-bold leading-tight text-5xl select-none mb-5 text-center">{{currentTranslation.end_header}}</h1>
                        <h2 class="font-weight-bolder leading-tight text-3xl select-none text-center">{{currentTranslation.end_message}}</h2>
        </div> 
        
       
    </div>
</template>

<script>


    export default {

        components:{
        },

        props:{
            
            currentTranslation:{
                type:Object,
                Required:true,
            },
        },
        
        mounted() {
        // Set a timeout to refresh the page after 30 minutes (1,800,000 milliseconds)
        setTimeout(() => {
            console.log('srart time out to reset step');
           this.$emit('resetstep');
        }, 5000); // 5 Seconds
    },
    
    
    data() {
        return {
          
        };
    },
    methods:{
        

       
    },
        
    }
</script>

<style lang="scss" scoped>

</style>