<template>
    <div class="filter-options flex items-center space-x-4 rtl:space-x-reverse ">
        <div>
            <label for="filter" class="text-sm font-medium text-gray-700">Filter By |</label>
        </div>
        <label :class="{'bg-secondary_blue text-white': selectedFilter == 'all'}" class="border-[1px] p-1 rounded-lg md:text-md border-gray-400 text-secondary_blue">
            <input class="hidden" type="radio" value="all" v-model="selectedFilter" @change="handleFilterChange">
            All
        </label>
        <label :class="{'bg-secondary_blue text-white': selectedFilter == 'image'}" class="border-[1px] p-1 rounded-lg md:text-md border-gray-400 text-secondary_blue">
            <input class="hidden" type="radio" value="image" v-model="selectedFilter" @change="handleFilterChange">
            Images
        </label>
        <label :class="{'bg-secondary_blue text-white': selectedFilter == 'video'}" class="border-[1px] p-1 rounded-lg md:text-md border-gray-400 text-secondary_blue">
            <input class="hidden" type="radio" value="video" v-model="selectedFilter" @change="handleFilterChange">
            Videos
        </label>
    </div>
</template>

<script>
export default {
    name: 'MediaFilter',
    props: {
        value: {
            type: String,
            default: 'all'
        }
    },
    data() {
        return {
            selectedFilter: this.value,
            translations: window.translations,

        };
    },
    watch: {
        value(newValue) {
            this.selectedFilter = newValue;
        }
    },
    methods: {
        handleFilterChange() {

            this.$emit('update', this.selectedFilter);
        }
    }
};
</script>

<style scoped>
/* Add any necessary styles here */
</style>
