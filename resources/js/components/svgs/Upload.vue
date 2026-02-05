<template>
    <div class="grid justify-center items-center ">
        <div class="flex justify-center items-center">
            <img class="w-24 h-24 object-contain" src="../../../../public/images/deafult/loading.svg" alt="">
        </div>
        <!-- <h1 v-if="viewTitle" class="text-xs text-center">Upload</h1> -->
    </div>




</template>
<script>
export default {
    name: 'Upload',

    props:{
        viewTitle:{
            type:Boolean,
            default:true,
        },
    },

}
</script>
