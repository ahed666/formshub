<template>
    <div v-if="formTranslations != null" class="flex space-x-2 rtl:space-x-reverse">
        <!-- languages buttons -->

        <div v-for="(formTranslation, index) in formTranslations" :key="index"
            @click="changeLanguage(formTranslation.language)" :class="['p-2 rounded flex justify-between items-center',
                {
                    'bg-white border-[1px] border-secondary_blue': formTranslation.language === currentLang,
                    'bg-gray-300': formTranslation.language !== currentLang
                }]">
            <div>
                <h1 v-if="languageNameEditingIndex != index">{{ formTranslation.language_name }}</h1>
                <input v-model="languageNameEditingText" v-else-if="languageNameEditingIndex == index" type="text"
                    class="rounded p-1 ">
            </div>
            <div class="flex justify-center items-center">
                <Edit v-if="languageNameEditingIndex != index" @click="enableEditLanguageName(index)" />
                <button v-if="languageNameEditingIndex == index" @click.stop="saveLanguageName()">{{translations.buttons.save}}</button>
            </div>

            <button v-if="formTranslation.language !== defaultLang&&currentLang==formTranslation.language" @click.stop="disableLanguage(currentLang)" 
            :class="['ml-2 text-xs  cursor-pointer',
                { 'text-valid': formTranslation.enable == false, 'text-red-500': formTranslation.enable == true }
            ]">
                {{ formTranslation.enable === true ? translations.titles.disable : translations.titles.enable }}
            </button>
        </div>




    </div>
</template>

<script>
import Edit from '../svgs/Edit.vue';
export default {
    components: {
        Edit,
    },
    props: {
        formTranslations: {
            type: Array,
            required: true
        },
        currentLang: {
            type: String,
            required: true
        },
        defaultLang: {
            type: String,
            required: true
        }
    },
    watch: {

    },
    data() {
        return {
            languageNameEditingIndex: null,
            languageNameEditingText: null,
            translations:window.translations,

        }
    },

    methods: {
        changeLanguage(lang) {
            this.$emit('changeLanguage', lang);
        },
        disableLanguage(lang) {
            console.log(this.formTranslations[this.currentLang].enable);
            this.$emit('disableLanguage', lang);
        },
        enableEditLanguageName(index) {
            this.languageNameEditingIndex = index;
            this.languageNameEditingText = this.formTranslations[this.languageNameEditingIndex].language_name;

        },
        saveLanguageName() {
            console.log('save lang name');
            this.formTranslations[this.languageNameEditingIndex].language_name = this.languageNameEditingText;
            this.$emit('changeLanguageName', this.languageNameEditingIndex, this.languageNameEditingText);
            this.languageNameEditingIndex = null;
            this.languageNameEditingText = null;

        },
    }
}
</script>

<style scoped>
/* Add any additional styles if needed */
</style>
