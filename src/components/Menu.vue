<template>
    <div class="menu" id="menu">
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
import blockMenu from './blocMenuComponent.vue'


const props = defineProps(['configMenu']);

console.log(props.configMenu)
</script>

<style scoped>


.menu{
    position: absolute;
    background-color: red;
    width: 10px;
    height: 10px;
}

</style>