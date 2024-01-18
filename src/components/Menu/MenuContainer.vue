<template>
    <div class="menuContainer" id="menu">
        <blockMenu
            v-for="(currentblockMenu, index) in props.configMenu"
            :type="currentblockMenu.type"
            :icon="currentblockMenu.icon"
            :name="currentblockMenu.name"
            :action="currentblockMenu.action"
            :key="index"/>
    </div>
</template>


<script setup>
import { ref, onMounted } from 'vue'
import blockMenu from './BlockMenu.vue'


const props = defineProps(['configMenu']);

console.log(props.configMenu)
</script>

<style scoped>

.menuContainer{
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