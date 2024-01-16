<template>
    <div class="blocMenu" @click="actionButton(props.action)">
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

.blocMenu{
    width: 6vw;
    height: 25px;

    display: flex;
    text-align: center;
    justify-content: flex-start;
    
    margin: 7px;
}

.blocMenu:hover{
    background-color: rgb(85, 85, 85);
    border-radius: 5px;
}

</style>