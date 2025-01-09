<template>
    <div class="flex flex-col items-center bg-gray-200 p-1 rounded-lg shadow-lg">
        <div :class="['grid grid-cols-12  w-full gap-1 mb-1']">
            <template v-for="(row, rowIndex) in displayedKeys" :key="rowIndex">

                <div v-for="(key, index) in row" :key="index" class="h-10 w-11 min-w-[44px] max-w-max border border-gray-300 rounded-lg p-[6px] text-center
                     cursor-pointer bg-white hover:bg-blue-100 transition" @click="handleKeyClick(key)">
                    {{ key }}
                </div>
            </template>
            <div class="grid grid-cols-12 col-span-12 w-full space-x-2 ">

                <button @click="toggleCase"
                    class="col-span-2 flex justify-center items-center p-[6px] bg-gray-300 rounded">
                    {{ isUpperCase ? 'Aa' : 'aA' }}
                </button>

                <button v-if="this.questionType !== 'email'" @click="switchLanguage"
                    class="col-span-2 flex justify-center items-center p-[6px] bg-gray-300 rounded">
                    {{ currentLanguage === 'en' ? 'عربي' : 'English' }}
                </button>

                <button @click="handleKeyClick(' ')"
                    class="col-span-4 flex justify-center items-center p-[6px] bg-gray-300 rounded">

                </button>

                <button @click="handleKeyClick('backspace')"
                    class="col-span-2 flex justify-center items-center p-[6px] bg-gray-300 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9.75 14.25 12m0 0 2.25 2.25M14.25 12l2.25-2.25M14.25 12 12 14.25m-2.58 4.92-6.374-6.375a1.125 1.125 0 0 1 0-1.59L9.42 4.83c.21-.211.497-.33.795-.33H19.5a2.25 2.25 0 0 1 2.25 2.25v10.5a2.25 2.25 0 0 1-2.25 2.25h-9.284c-.298 0-.585-.119-.795-.33Z" />
                    </svg>

                </button>

                <button @click="switchToNumbers"
                    class="col-span-2 flex justify-center items-center p-[6px] bg-gray-300 rounded">
                    123
                </button>
            </div>

        </div>




    </div>
</template>

<script>
export default {
    props: {
        questionType: {
            type: String,
            required: true,
        },
    },
    data() {
        return {
            isUpperCase: false,
            showNumbers: false,
            currentLanguage: 'en', // Default language
            textKeys: {
                en: [
                    ['q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p'],
                    ['a', 's', 'd', 'f', 'g', 'h', 'j', 'k', 'l'],
                    ['z', 'x', 'c', 'v', 'b', 'n', 'm'],
                ],
                ar: [
                    ['ض', 'ص', 'ث', 'ق', 'ف', 'غ', 'ع', 'ه', 'خ', 'ح'],
                    ['ش', 'س', 'ي', 'ب', 'ل', 'ا', 'ت', 'ن', 'م'],
                    ['ط', 'ئ', 'ر', 'ذ', 'د', 'ز', 'و', 'ى'],
                ],
            },
            numberKeys: [
                ['1', '2', '3'],
                ['4', '5', '6'],
                ['7', '8', '9'],
                ['0', 'backspace'],
            ],
            symbolKeys: [
                ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '+'],
                ['-', '*', '^', '&', ':', ';', '<', '>', ')', '.', '('],
                ['%', '!', '#', '\\', '$', '/', '?', '"', '\'', ','],

            ],
        };
    },
    computed: {
        displayedKeys() {
            if (this.questionType === 'short_text_question' || this.questionType === 'long_text_question') {
                return this.showNumbers ? this.symbolKeys : this.textKeys[this.currentLanguage].map(row => row.map(key => this.isUpperCase ? key.toUpperCase() : key));
            } else if (this.questionType === 'email') {
                return this.showNumbers ? this.symbolKeys : [['1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '.com', '@'], ...this.textKeys['en'].map(row => row.map(key => this.isUpperCase ? key.toUpperCase() : key))];
            } else if (this.questionType === 'number') {
                return this.numberKeys;
            }
            return [];
        },
    },
    methods: {
        handleKeyClick(key) {
            if (key === 'backspace') {
                this.$emit('backspace');
            } else {
                this.$emit('key-press', key);
            }
        },
        toggleCase() {
            this.isUpperCase = !this.isUpperCase;
        },
        switchToNumbers() {
            this.showNumbers = !this.showNumbers;
        },
        switchLanguage() {
            this.currentLanguage = this.currentLanguage === 'en' ? 'ar' : 'en';
        },
        clearInput() {
            this.$emit('clear-input');
        },
    },
};
</script>

<style scoped>
/* Add any additional styles here */
</style>