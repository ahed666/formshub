<template>
    
   
<svg :class="[dimClass,'text-secondary_blue']" xmlns="http://www.w3.org/2000/svg" fill="#e0e0e0" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
</svg>


    
    </template>
    
    <script>
        export default {
          props:{
            dimClass:{
              type:String,
              required: false,
              default:'md:w-16 md:h-16 xs:w-10 xs:h-10',
            },
          },
            
        }
    </script>
    
    <style lang="scss" scoped>
    
    </style>