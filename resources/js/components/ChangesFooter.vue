<template>
 <footer v-if="ifEdit" class="fixed bottom-2 left-0 right-0 md:ml-72 2xl:max-w-[80%] xl:max-w-[90%] lg:max-w-[90%] md:max-w-[80%] xs:max-w-full bg-white
  md:p-2  xs:p-1 border-[1px] border-secondary_blue rounded-lg md:mr-8 mx-2">
            <div class="flex  justify-between items-center">

                <slot></slot>
            </div>
        </footer>
</template>
<script>


export default {
    components:{

    },
    props: {

        ifEdit: {
            type: Boolean,
            default: false,
        },
    },





};
</script>
