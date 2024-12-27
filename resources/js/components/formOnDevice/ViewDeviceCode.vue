<template>

    <div class="grid justify-center items-center p-14 min-h-screen max-h-screen">
        <div class="grid justify-center items-center mb-4 max-h-[50%]">
            <img :src="logoPath" class="w-full object-contain h-16" alt="" srcset="">
            <h1 class="mt-2">        To connect your device to the web app, use the unique <strong>PIN</strong> provided.             </h1>
        </div>
        <div class="flex justify-between space-x-6 font-bold items-center max-h-[50%] ">
            <div v-for="(digit, index) in pin" :key="index" class="p-2 text-black font-bold text-5xl">
                {{ digit }}
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        pin: {
            type: String,
            Required: true,
        },
    },
    data(){
        return {
            logoPath:'/images/logo/app/main-logo.png',
        }
    }
}
</script>

<style lang="scss" scoped></style>