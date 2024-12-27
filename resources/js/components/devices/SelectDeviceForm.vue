<template>
    <div class="">
            <Loader v-if="isLoading" />
            <ul v-if="!isLoading" class="flex flex-col max-h-[40vh] overflow-y-auto">
            <!-- template for each form -->
            <li @click="setFormToDevice(form.id)" class="w-full bg-white my-2 p-2 border rounded hover:cursor-pointer" 
                :class="{ 'border-secondary_blue': form.id === deviceFormId, 'animate-pulse': isLoading }"
                v-for="form in forms" :key="form.id">

                <label class="flex justify-between items-center space-x-4 rtl:space-x-reverse">
                    <!-- Check mark on the left side -->
                    <span v-if="form.id === deviceFormId" class="text-secondary_blue font-bold">✔</span>
                    
                    <!-- Form name and questions count -->
                    <div class="flex items-between space-x-4 rtl:space-x-reverse">
                        <h1 class="ml-2 text-sm font-bold">{{ form.name }}</h1>
                        <NumFormQuestions :questions_count="form.questions_count"></NumFormQuestions>
                    </div>

                    
              
                </label>
            </li>
        </ul>
        
    </div>
</template>

<script>
import Loader from '../svgs/Loader.vue';
import NumFormQuestions from '../forms/Counter.vue';
export default {
    components:{Loader,NumFormQuestions},
    props: {

        deviceFormId:{
            type:Number,
            Required:false,
            default: null,
        },
        
    },

    data(){
        return{
            isLoading:false,
            forms:[],
            translations: window.translations,


        }
    },
    mounted(){
       this.getForms();
    },
   
    methods: {
         // get all forms
         async getForms() {
            this.isLoading = true;

            // get all playlists
            try {
                const response = await axios.get('/getforms');
                this.forms = response.data.forms;
                console.log(response.data.forms);

            } catch (error) {
                console.error('Error fetching media:', error);

            }

            this.isLoading = false;
        },

        setFormToDevice(formId){
            console.log(formId);
            this.$emit('setForm',formId)
        }
        
    }
}
</script>

<style scoped>
/* Add any additional styles if needed */
</style>
