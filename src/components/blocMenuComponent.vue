<template>
    <div class="sousMenu">
        {{ props.name }}
    </div>
</template>


<script setup>

const props = defineProps(['type','icon','name','action']);

</script>

<style scoped>

.sousMenu{
    margin: 10px;
    width: 100px;
    height: 10px;
    background-color: blue;

    text-align: center;
}

</style>