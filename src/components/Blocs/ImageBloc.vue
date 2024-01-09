<template>

  <div class='resizable' @mousedown.stop>
    <img class="image" src="https://imgupscaler.com/images/samples/animal-after.webp">
  </div>

</template>
  
<script setup>

</script>
  
<style scoped>
  
  .resizable {
    display: inline-block;
    background: red;
    resize: both;
    overflow: hidden;
    line-height: 0;

    min-width: 200px;
    max-width: 1000px;

    min-height: 100px;

    width: max-content !important;


  }


  .image {
    min-width: 200px;
    max-width: 1000px;

    width: 100%;
    height: 100%;

    object-fit: cover;
  }
</style>
  