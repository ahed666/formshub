<template>
    <div
        :class="['flex  justify-center items-center bg-gray-200 p-1 rounded-lg shadow-lg', { 'opacity-50  pointer-events-none': !enable }]">
        <div :class="['grid col-span-4  w-full gap-1 mb-1']">
            <div v-for="(row, rowIndex) in numberKeys" :key="rowIndex" class="flex justify-center space-x-3">

                <div v-for="(key, index) in row" :key="index" class="border w-full border-gray-300 rounded-lg p-[6px] text-center
                     cursor-pointer bg-white hover:bg-blue-100 transition" @click="handleKeyClick(key)">
                    {{ key }}
                </div>
            </div>
            <div class="flex justify-between">


                <!-- Zero button -->
                <div @click="handleKeyClick('0')"
                    class="border border-gray-300 rounded-lg p-[6px] text-center cursor-pointer bg-white hover:bg-blue-100 transition w-full mr-1">
                    0
                </div>
                <div @click="handleKeyClick('backspace')"
                    class="border border-gray-300 rounded-lg p-[6px] text-center cursor-pointer bg-white hover:bg-blue-100 transition w-full mr-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9.75 14.25 12m0 0 2.25 2.25M14.25 12l2.25-2.25M14.25 12 12 14.25m-2.58 4.92-6.374-6.375a1.125 1.125 0 0 1 0-1.59L9.42 4.83c.21-.211.497-.33.795-.33H19.5a2.25 2.25 0 0 1 2.25 2.25v10.5a2.25 2.25 0 0 1-2.25 2.25h-9.284c-.298 0-.585-.119-.795-.33Z" />
                    </svg>

                </div>


            </div>

        </div>




    </div>
</template>

<script>
export default {
    props: {
        enable: {
            type: Boolean,
            required: true,
            default: false,
        },

    },
    data() {
        return {


            numberKeys: [
                ['1', '2', '3'],
                ['4', '5', '6'],
                ['7', '8', '9'],
             
            ],

        };
    },
    computed: {

    },
    methods: {
        handleKeyClick(key) {
            if (key === 'backspace') {
                this.$emit('backspace');
            } else {
                this.$emit('key-press', key);
            }
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