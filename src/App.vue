<template>
  <template v-if="!isLogged">
    T'es pas connecté
    <!-- <MainPage @loginSuccess="updateIsLogged" /> -->
  </template>
  <template v-else-if="isLogged">
    <Canvas/>
  </template>
</template>

<script setup lang="ts">
 
  import Canvas from './components/Canvas.vue';
  // import MainPage from './components/MainPage.vue';

  import { ref } from 'vue';


  const isLogged = ref(false);

  function updateIsLogged() {
    isLogged.value = true;
  }

  updateIsLogged()
 
</script>




<style scoped>
</style>