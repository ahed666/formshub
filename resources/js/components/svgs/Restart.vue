<template>
  
        <!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">

        <!-- Uploaded to: SVG Repo, www.svgrepo.com, Transformed by: SVG Repo Mixer Tools -->
        <svg :title="title"  fill="#878787" class="mx-1 w-6 h-6 text-svg_primary hover:text-secondary_blue hover:cursor-pointer"
         version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
            xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 52 52" xml:space="preserve" stroke="#878787">
            <title>{{ title }}</title>
            <g id="SVGRepo_bgCarrier" stroke-width="0" />
            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" />
            <g id="SVGRepo_iconCarrier">
                <g>
                    <path
                        d="M26,0C11.663,0,0,11.663,0,26s11.663,26,26,26s26-11.663,26-26S40.337,0,26,0z M26,50C12.767,50,2,39.233,2,26 S12.767,2,26,2s24,10.767,24,24S39.233,50,26,50z" />
                    <polygon points="32,36.783 32,15.438 14.043,25.806 " />
                </g>
            </g>
        </svg>
    
</template>

<script>
export default {
    props:{
          title:{
            type:String,
            default:'Restart',
          },
        },
}
</script>

<style lang="scss" scoped></style>