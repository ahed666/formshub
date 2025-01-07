<template>
    <div class="grid justify-center items-center p-14 min-h-screen max-h-screen">
          <div class="animate-bounce">
            <UploadSvg />
          </div>
          <h1 class="mt-1 text-bold">Uploading Response..</h1>
       
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
        setTimeout(() => {
            window.location.reload(); // Refresh the page
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