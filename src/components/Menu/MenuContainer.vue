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

    background-color: rgb(43, 43, 43);
    border: 2px rgb(90, 90, 90) solid;
    border-radius: 15px;
}


</style>