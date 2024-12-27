<template>
    <ul class="max-h-full pr-2 overflow-y-auto">
        <li class="w-full bg-white  my-2 p-2 border rounded" v-for="(form,index) in forms" :key="index">
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