<template>
    <div class="menuContainer" id="menu">
      <block-menu
        v-for="(currentBlockMenu, index) in configMenu"
        :type="currentBlockMenu.type"
        :icon="currentBlockMenu.icon"
        :name="currentBlockMenu.name"
        :action="currentBlockMenu.action"
        :key="index"
      />
    </div>
  </template>
  
  <script setup lang="ts">
  import { defineProps } from 'vue';
  import BlockMenu from './BlockMenu.vue';
  
  const props = defineProps(['configMenu']);
  </script>
  
  <style scoped>
  .menuContainer {
    position: absolute;
  
    display: flex;
    flex-direction: column;
    justify-content: space-around;
    align-content: left;
  
    background-color: var(--main-div-color);
    border: 2px var(--border-color) solid;
    border-radius: var(--small-border-radius);
  }
  </style>
  