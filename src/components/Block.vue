<template>

<div class="block">
    {{ testBlock.type }}
</div>

</template>

<script setup>
import { ref } from 'vue';
import { blocks } from '@/helpers/blockHelper.js';

const localBlocks = JSON.parse(JSON.stringify(blocks));

let testBlock = localBlocks[0];
</script>

<style scoped>

.block {
    padding: 1rem;
    background: #fff2;
}

</style>