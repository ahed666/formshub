<template>

    <div class="grid justify-center p-14 min-h-screen max-h-screen">
        <div class=" mb-4">
            <img :src="logoPath" class="w-full object-contain h-16" alt="" srcset="">
            <h1 class="mt-2">        To connect your device to the web app, use the unique <strong>PIN</strong> provided.             </h1>
        </div>
        <div class="flex  space-x-1 font-bold items-baseline ">
            <div v-for="(digit, index) in pin" :key="index" class="flex justify-center items-center w-full h-auto p-2 text-black font-bold text-6xl text-center border-[1px] border-black">
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