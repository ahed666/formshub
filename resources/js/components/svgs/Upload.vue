<template>
    <div class="grid justify-center items-center ">
        <div class="flex justify-center items-center">
            <svg  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
            stroke-width="1.5" stroke="currentColor" class="mx-1 w-6 h-6 text-svg_primary hover:text-secondary_blue  hover:cursor-pointer">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M7.5 7.5h-.75A2.25 2.25 0 0 0 4.5 9.75v7.5a2.25 2.25 0 0 0 2.25 2.25h7.5a2.25 2.25 0 0 0 2.25-2.25v-7.5a2.25 2.25 0 0 0-2.25-2.25h-.75m0-3-3-3m0 0-3 3m3-3v11.25m6-2.25h.75a2.25 2.25 0 0 1 2.25 2.25v7.5a2.25 2.25 0 0 1-2.25 2.25h-7.5a2.25 2.25 0 0 1-2.25-2.25v-.75" />
        </svg>
        </div>
        <h1 v-if="viewTitle" class="text-xs text-center">Upload</h1>
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
