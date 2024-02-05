<template>
    <div class="blockMenu" @click="actionButton(props.action)">
        {{ props.name }}
    </div>
</template>


<script setup>
import { ref } from 'vue';
// import { newBlock } from '@/helpers/blockHelpers.js'
import { blocks, newBlock } from '@/helpers/blockHelper.js';
import { Store } from '@/store.js'
import {configsMenu} from '@/helpers/configMenu.js'

const storage = Store()

const props = defineProps(['type','icon','name','action']);

// function call when current button is click
function actionButton(action){
    switch(action){
        case "addBlockImage":
            // Create block
            newBlock("image", storage.mouseX - storage.coordCanvas.x, storage.mouseY - storage.coordCanvas.y)
            // Clear menu
            storage.menus = []
            break;
        case "addBlockText":
            // Create block
            newBlock("text", storage.mouseX - storage.coordCanvas.x , storage.mouseY - storage.coordCanvas.y)
            // Clear menu
            storage.menus = [] 
            break;
        case "openMenuAddBlock":
            storage.menus.push({
                position: {x: storage.mouseX + 175,y: storage.mouseY + 0},
                scale: {x: 1,y: 1},
                config: configsMenu.addBlockMenu,
            })
            break;
    }
}

</script>

<style scoped>

.blockMenu{
    width: 10vw;
    height: 25px;

    display: flex;
    text-align: center;
    justify-content: flex-start;
    
    margin: 7px;
}

.blockMenu:hover{
    background-color: rgb(85, 85, 85);
    border-radius: 5px;
}

</style>