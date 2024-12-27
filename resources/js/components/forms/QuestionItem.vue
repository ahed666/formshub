<template>
    <div @mouseenter="showOverlay = true" @mouseleave="showOverlay = false"  >

        <label class="flex justify-start items-center relative max-h-7 h-7  p-1">
            <input type="checkbox" :value="question.id" :checked="isSelected" @click.stop="toggleSelection"
                class="form-checkbox   h-5 w-5 rounded-full text-blue-600 mr-2" />
        </label>
        <div class="relative">


            <img :src="question.thumbnail_path" class=" h-40 w-full object-contain"
                :class="{ 'animate-pulse': isLoading, 'opacity-20': showOverlay }">

            <!-- video or  image button to show -->
            <ShowQuestion :question="question" :class="'max-w-16 max-h-16 h-16 w-16'" ></ShowQuestion>

        </div>
        <div  class="grid bg-[#00ced1] p-1 mt-2 min-h-14">
            <!-- type and duration -->
            <div class="flex justify-between items-center my-1">

                <div class="text-xs left-2 bottom-2 text-gray-600   rounded-lg p-[2px] ">
                    <span>{{ question.type }}</span>
                </div>

                

            </div>
             <!-- question name -->
            <div class="text-xs">
                <div class="grid grid-cols-5 justify-between items-center" v-if="question.id !== editedQuestionId">
                      <!-- <div class="overflow-hidden grow max-h-4 text-ellipsis whitespace-nowrap">
                        <span class="max-w-[80%]"  data-bs-toggle="tooltip"  data-bs-html="true" :title="question.name">{{ question.name }}</span>
                      </div> -->
                     <h1 class="col-span-4 overflow-hidden max-h-4 text-ellipsis whitespace-nowrap  "> {{ question.name }}</h1>
                     <div class="flex justify-end col-span-1">
                        <EditSvg  v-if="allowEdit"   @click="enableEditName(question)"/>

                     </div>


                </div>
                <div v-if="allowEdit && question.id === editedQuestionId" class="flex items-center justify-between space-x-2 rtl:space-x-reverse">
                    <input  type="text" v-model="editedName" :value="editedName" name="name" class="p-[2px]  border-[1px] border-gray-200 rounded-lg w-full text-xs">
                    <button @click="saveEditedName(question)" class='bg-secondary_blue hover:bg-blue-700 w-10 p-1 rounded-lg text-white  '>
                        {{ translations.buttons.save }}
                    </button>


                </div>
            </div>

        </div>










        <!-- delete item -->
        <!-- <div class="right-0 top-0 bg-white rounded-full p-1 absolute">
                                        <DeleteSvg @click.stop="deleteSingleQuestionItem(question.id)">

                                        </DeleteSvg>
                </div> -->
    </div>
</template>

<style scoped>
/* Styling for hover effect */
label:hover .bg-gray-200.opacity-20 {
    opacity: 0.8;
    /* Adjust opacity as needed */
}
</style>
<script>
import ButtonComponent from '../actions/ButtonComponent.vue';
import EditSvg from '../svgs/Edit.vue';
import ShowQuestion from '../actions/ShowQuestion.vue';
export default {
    components: {
        ButtonComponent,
        EditSvg,
        ShowQuestion,
    },
    props: {
        allowEdit: {
            type: Boolean,
            default: false,

        },
        question: {
            type: Object,
            required: true,
        },
        selectedQuestion: {
            type: Array,
            required: true,
        },
        isLoading: {
            type: Boolean,
            required: true,
        }

    },
    data() {
        return {
            showOverlay: false, // Track if overlay should be shown on hover
            translations:window.translations,

            editedName: '',
            editedQuestionId: null,

        };
    },
    mounted() {

    },
    computed: {
        isSelected() {
            return this.selectedQuestion.includes(this.question.id);
        },
    },
    methods: {

        enableEditName(question) {
            this.editedName = question.name;
            this.editedQuestionId = question.id;
        },
        saveEditedName(question){
            // Send formData to server using Axios or another HTTP library
            axios.put('/editquestionname',{
                id: this.editedQuestionId,
                name: this.editedName
            })
                .then(response => {
                    console.log('edited successful:');
                    question.name=this.editedName;
                    this.editedQuestionId=null;
                })
                .catch(error => {
                    this.editedQuestionId=null;

                    console.error('Error while edited');
                });

        },


        formatDuration(seconds) {
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = seconds % 60;
            return `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
        },
    
        showControls(event) {
            event.target.controls = true;
        },
        hideControls(event) {
            event.target.controls = false;
        },

        // toggle select item
        toggleSelection() {
            this.$emit('update:selectedQuestion', this.question);
        },




    },




};
</script>
