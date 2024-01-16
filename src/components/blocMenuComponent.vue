<template>
    <div class="sousMenu" @click="actionButton(props.action)">
        {{ props.name }}
    </div>
</template>


<script setup>

const props = defineProps(['type','icon','name','action']);

// function call when current button is click
function actionButton(action){
    switch(action){
        case "":
            break;
    }
}

</script>

<style scoped>

.sousMenu{
    margin: 10px;
    width: 100px;
    height: 10px;
    background-color: blue;

    text-align: center;
}

.sousMenu:hover{
    background-color: red;
}

</style>