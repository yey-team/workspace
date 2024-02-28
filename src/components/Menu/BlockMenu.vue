<template>
    <div class="blockMenu" @click="actionButton(props.action)">
        {{ props.name }}
    </div>
</template>
  
<script setup lang="ts">
    import { useBlockStore } from '@/helpers/blockHelper';
    import { useMenusStore, useMouseStore } from '@/helpers/store';
    import configsMenus from '@/helpers/configMenu';

    
    const exportedData = useBlockStore()

    const newBlock = exportedData.newBlock;
    const menusStore = useMenusStore();
    const mouseStore = useMouseStore();
    const props = defineProps(['type', 'icon', 'name', 'action']);
    
    
    // Fonction appelée lorsque le bouton actuel est cliqué
    function actionButton(action: string): void {
        switch (action) {
            case 'addBlockImage':
                newBlock('image', mouseStore.mouseX, mouseStore.mouseY);
                // Clear menu
                menusStore.menus = []
                break;
            case 'addBlockText':
                newBlock('text', mouseStore.mouseX, mouseStore.mouseY);
                // Clear menu
                menusStore.menus = []
                break;
            case "openMenuAddBlock":
                menusStore.addMenu({
                    position: {x: mouseStore.mouseX + 175,y: mouseStore.mouseY + 0},
                    scale: {x: 1,y: 1},
                    config: configsMenus.configsMenus.addBlockMenu,
                })
                break;
            case "linkBlocks":
                
                break;
        }
    }
</script>
  
<style scoped>
    .blockMenu {
        width: 10vw;
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
  