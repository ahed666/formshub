<template>
    <div class="grid justify-center items-center p-14 min-h-screen max-h-screen">
          <div class="animate-bounce">
            <UploadSvg />
          </div>
        
       
    </div>
</template>

<script>

 import UploadSvg from '../svgs/Upload.vue';
    export default {

        components:{UploadSvg
        },

        props:{
            
            
        },
        
        mounted() {
        // Set a timeout to refresh the page after 30 minutes (1,800,000 milliseconds)
        
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