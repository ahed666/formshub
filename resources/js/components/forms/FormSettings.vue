<template>
    <div class=" xl:col-span-4 lg:col-span-4 md:col-span-12 xs:col-span-12 items-center  rounded-lg border-[1px] border-secondary_blue 2xl:p-4 md:p-2 xs:p-2 max-h-[90vh] overflow-auto">
        <div class="flex text-lg font-bold flex-col justify-start items-center mb-2 ">
            {{ translations.titles.settings_title }}
        </div>
        <div class="">
            <!-- form name -->
            <div v-if="currentForm" class="my-4">
                <EditFormName :formName="currentForm.name" @editFormName="editFormName"
                    :slug="currentForm.slug" />
            </div>

            <!-- form Logo -->
            <div v-if="currentForm" class="my-4 flex justify-start">
                <FormLogo :defaultImage="currentForm.logo" @image-selected="onImageSelected"></FormLogo>
            </div>



        </div>
    </div>
</template>

<script>
import EditFormName from '../actions/EditFormName.vue';
import FormLogo from './FormLogo.vue';

    export default {

        components:{
            EditFormName,FormLogo
        },

        props:{
            currentForm:{
                type:Object,
                Required:true,
            },
        },
        data(){
            return {
                translations: window.translations,

            }
        },

        methods:{
        editFormName(newName) {
            this.$emit('editFormName', newName);
        },
        onImageSelected(imageFile) {
            console.log('changing logo form');
            this.$emit('onImageSelected', imageFile);

        }
        },

        
    }
</script>

<style lang="scss" scoped>

</style>