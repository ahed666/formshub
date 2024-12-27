<template>
    <div v-if="!isLoading && formTranslations != null" :class="{ 'animate-pulse': isLoading }"
        class="gap-2 xl:col-span-8 lg:col-span-8 md:col-span-12 xs:col-span-12
         rounded-lg border-[1px] border-secondary_blue min-h-[50vh] max-h-[90vh] p-4">

        <div class="max-h-[10vh] mb-2 w-full flex justify-between items-center space-x-4 rtl:space-x-reverse">
            <!-- formTranslations buttons -->
            <div>
                
                <LanguageTabs :formTranslations="formTranslations" :currentLang="currentLang" :defaultLang="defaultLang"
                    @changeLanguage="changeLanguage" 
                    @disableLanguage="disableLanguage"
                    @changeLanguageName="onChangeLanguageName" />
            </div>
            <!-- add question component -->
            <div class="flex justify-between items-center space-x-3 rtl:space-x-reverse">
                <AddQuestion @addquestion="showModal('addquestion')"></AddQuestion>
                <h1>{{ formQuestions.length }} <span class="mx-[1px]">{{ translations.forms.questions_unit }}</span></h1>
            
            </div>
        </div>

        <div :class="['overflow-y-auto max-h-[70vh]', { 'opacity-50': formTranslations ? !formTranslations[currentLang].enable : false }]">

            <!-- start message -->
            <FormMessage @editMessage="showModal('editformmessagestart')" :type="'Start Page'"
                :formHeader="formTranslations ? formTranslations[currentLang].start_header : null"
                :formMessage="formTranslations ? formTranslations[currentLang].start_message : null">
            </FormMessage>

            <!-- questions -->
            <div :class="{ 'hidden': isLoading }" class="min-h-[50vh] flex justify-center items-center "
                v-if="formQuestions.length === 0">
                <span>{{translations.messages.no_forms_avilable}}</span>
            </div>
            <div v-else>
                <!-- allow drag and drop for question item of form by draggable -->
                <draggable class="grid gap-2" v-model="localFormQuestions" @start="drag = true" @end="onDragEnd"
                    :scroll="true" :scroll-sensitivity="30" :scroll-speed="10">

                    <div :class="{ 'animate-pulse': isLoading }"
                        class="hover:cursor-move item border-[1px] border-secondary_blue rounded-lg p-2 bg-white"
                        v-for="question in localFormQuestions" :key="question.id">
                        <!--  item of form  -->
                        <FormQuestionItem 
                            @setSelectedEditQuestion="setSelectedEditQuestion" 
                            @deleteQuestion="deleteQuestion"
                            :currentLang="currentLang" 
                            :question="question" >
                        </FormQuestionItem>
                    </div>
                </draggable>
            </div>

            <!-- end message -->
            <FormMessage @editMessage="showModal('editformmessageend')" :type="'End Page'"
                :formHeader="formTranslations ? formTranslations[currentLang].end_header : null"
                :formMessage="formTranslations ? formTranslations[currentLang].end_message : null">
            </FormMessage>

        </div>
    </div>
</template>

<script>
import LanguageTabs from './LanguageTabs.vue';
import AddQuestion from '../actions/AddQuestion.vue';
import FormQuestionItem from './FormQuestionItem.vue';
import FormMessage from './FormMessage.vue';
import { VueDraggableNext } from 'vue-draggable-next';
import { MessageMixin } from '../mixins/MessageMixin';
export default {
    mixins: [MessageMixin], 
    components: {
        LanguageTabs,
        AddQuestion,
        FormQuestionItem,
        FormMessage,
        draggable: VueDraggableNext,
    },
    props: {
        formQuestions: {
            type: Array,
            required: true,
        },
        formTranslations: {
            type: Object,
            required: true,
        },
        currentLang: {
            type: String,
            required: true,
        },
        isLoading: {
            type: Boolean,
            default: false,
        },
        maxItems: {
            type: Number,
            required: true,
        },
        defaultLang:{
            type:String,
            
        },
    },
    data() {
        return {
            localFormQuestions:[...this.formQuestions], // Create a local copy of the prop
            drag: false,
            translations:window.translations,

        };
    },
    watch: {
        formQuestions:{
            immediate: true, // Trigger immediatel
            handler(newQuestions) {
                console.log('new question add',newQuestions);
                this.localFormQuestions =[...this.formQuestions]; // Ensure full reactivity

            }
        }
        
    },
    methods: {
        changeLanguage(lang) {
            this.$emit('changeLanguage', lang);
        },
        disableLanguage(langCode) {
            this.$emit('disableLanguage', langCode);
        },
        onChangeLanguageName(index, value) {
            this.$emit('onChangeLanguageName', index, value);
        },
        showModal(type) {
            this.$emit('showModal',type);
        },
        setSelectedEditQuestion(question) {
            this.$emit('setSelectedEditQuestion', question);
        },
        deleteQuestion(deletedQuestion) {
            this.$emit('deleteQuestion', deletedQuestion);
        },
        onDragEnd() {
            // Emit updated questions order to the parent
            this.$emit('updateQuestionsOrder', this.localFormQuestions);
        },
    },
};
</script>

