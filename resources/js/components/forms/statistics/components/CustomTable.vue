<template>
    <div class="overflow-y-auto w-full" >
      <table class="min-w-full table-auto border border-gray-200">
        <!-- Table Header -->
        <thead class="bg-gray-100 sticky top-0 z-10">
          <tr>
            <th v-for="(column, index) in columns" :key="index" class="py-2 px-4 border-b font-semibold text-left">
           {{ column.label }}
          </th>
          </tr>
        </thead>
        <!-- Table Body -->
        <tbody>
            <tr v-for="(row, rowIndex) in data" :key="rowIndex" class="hover:bg-gray-50">
        <td v-for="(col, colIndex) in columns" :key="colIndex" class="flex space-x-1 rtl:space-x-reverse items-center py-2 px-4 border-b">
          <component
             class="max-w-28"
            v-if="col.component"
            :is="col.component"
            v-bind="col.props(row)"
          ></component>
          <span >{{ row[col.field] }}</span>
        </td>
      </tr> 
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
  import ProgressBar from './ProgressBar.vue';

  export default {
    components:{
        ProgressBar
    },
    props: {
        data: {
      type: Array,
      required: true
    },
    columns: {
      type: Array,
      required: true
    },
      actions: {
        type: Array,
        default: () => []
      },
      
    }
  }
  </script>
  
  <style scoped>
  /* Add additional styling if needed */
  </style>
  