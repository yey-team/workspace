<template>
    <div class="blockMenu" @click="actionButton($parent, props.action)">
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
    
    const intersectionMenuX: number = 5

    // Fonction appelée lorsque le bouton actuel est cliqué
    function actionButton(parent: any, action: string): void {
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
                // console.log(parent.$el.scrollWidth)
                let widthParent : number = parent.$el.scrollWidth
                menusStore.addMenu({
                    position: {x: mouseStore.mouseX + widthParent + intersectionMenuX,y: mouseStore.mouseY + 0},
                    scale: {x: 1,y: 1},
                    config: configsMenus.configsMenus.addBlockMenu,
                })
                break;
        }
    }
</script>
  
<style scoped>
    .blockMenu {
        width: 100%;
        margin: 7px 0;
        padding: 3% 1%;
    }
    .blockMenu:hover {
        background-color: rgb(85, 85, 85);
        border-radius: 5px;
    }
</style>
  