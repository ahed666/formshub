<!-- EditAction.vue -->
<template>
<a data-bs-toggle="tooltip" data-bs-html="true" title="{{ message }}">
                                <svg class="inline-block text-blue-400 w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"></path>
                            </svg></a>
                            
</template>

<script>

export default {
name: 'Tooltip',
components:{
    
},
data(){
return{
    translations: window.translations,

}
},
props:{
    
    title:{
        type:String,
        Required:false,

    }
    
},

methods: {
   
}
}
</script>

<style scoped>
/* Add any necessary styles here */
</style>
