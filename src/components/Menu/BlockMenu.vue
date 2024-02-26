<template>
    <div class="blockMenu" @click="actionButton(props.action)">
        {{ props.name }}
    </div>
</template>
  
<script setup lang="ts">
    import { defineProps } from 'vue';
    import exportedData from '@/helpers/blockHelper';
    import useStore from '@/store';

    
    const blocks = exportedData.blocks;
    const newBlock = exportedData.newBlock;
    const store = useStore();
    const props = defineProps(['type', 'icon', 'name', 'action']);
    
    
    // Fonction appelée lorsque le bouton actuel est cliqué
    function actionButton(action: string): void {
        switch (action) {
            case 'addBlockImage':
                newBlock('image', store.mouseX, store.mouseY);
                // Clear menu
                storage.menus = []
                break;
            case 'addBlockText':
                newBlock('text', store.mouseX, store.mouseY);
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
    .blockMenu {
        width: 6vw;
        height: 25px;
        display: flex;
        text-align: center;
        justify-content: flex-start;
        margin: 7px;
    }
    .blockMenu:hover {
        background-color: rgb(85, 85, 85);
        border-radius: 5px;
    }
</style>
  