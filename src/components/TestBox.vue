<template>
    <div class="container" :style="{ top: position.y + 'px', left: position.x + 'px' }">
      <!-- Votre contenu personnalisé -->
      {{ content }}
    </div>
  </template>
  
  <script setup>
  const props = defineProps(['position', 'content']);
  </script>  

<style scoped>

    .container{
        position: relative;
        background-color: var(--main-div-color);

        padding: 1rem;

        border-radius: var(--large-border-radius);
        border: 1px solid var(--border-color);
        box-shadow: 0px 5px 5px 0px rgba(0, 0, 0, 0.25);

        width: 200px;
        height: 100px;
    }

</style>