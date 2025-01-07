<template>
    <ul class="h-48 pr-2 overflow-y-auto">
        <li class="w-full   my-2 p-2 rounded-[0.5rem] bg-gray-100 inset-0" v-for="(form,index) in forms" :key="index">
            <FormCard class="hover:cursor-pointer flex justify-between items-center" @dblclick="goToEditForm(form)"  :form="form" :viewOptions="false" />
            
        </li>

    </ul>
</template>

<script>

import FormCard from '../forms/FormCard.vue';
    export default {
        components:{FormCard},
        props:{
            forms:{
            type:Array,
            }
        },
        methods:{
            goToEditForm(form){
                window.location.href = `/editform/${form.slug}`;
            }
        },

    }
</script>

<style lang="scss" scoped>

</style>