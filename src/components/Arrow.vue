<template>
    <!-- <div class="arrow" :style="{ top: `${tempArrowPosition.y}px`, left: `${tempArrowPosition.x}px` }"> -->
    
    <div class="arrow" :style="arrowStyle">
    
        <!-- Arrow -->
    
    </div>
</template>




<script setup lang="ts">
/* -------------------------------------------------------------------------- */
/*                                   Imports                                  */
/* -------------------------------------------------------------------------- */

import { ref, onMounted, onUnmounted, computed } from 'vue';
import { useArrowStore } from '@/helpers/arrowsHelper';
import { useBlockStore } from '@/helpers/blockHelper';
import { useCanvasStore } from '@/helpers/store';

/* -------------------------------------------------------------------------- */
/*                                  Variables                                 */
/* -------------------------------------------------------------------------- */


const exportedBlockData = useBlockStore()
const exportedArrowsData = useArrowStore()

const props = defineProps(["id"])

const currentArrow = exportedArrowsData.getArrowById(props.id);

const tempArrowPosition: any = ref(undefined)
const currentArrowPosition: any = ref(undefined)

const firstBlockPosition = ref({ x: 0, y: 0 })
const secondBlockPosition = ref({ x: 0, y: 0 })

if (currentArrow) {

    const firstBlock = exportedBlockData.getBlockById(currentArrow.firstBlock);
    const secondBlock = exportedBlockData.getBlockById(currentArrow.secondBlock);

    if (firstBlock && secondBlock) {
        firstBlockPosition.value = firstBlock.position
        secondBlockPosition.value = secondBlock.position
    }

    updateArrowPosition(firstBlock, secondBlock)
}




/* -------------------------------------------------------------------------- */
/*                                Create arrow                                */
/* -------------------------------------------------------------------------- */


const arrowStyle = computed(() => {
    if (currentArrowPosition.value) {
      const dx = currentArrowPosition.value.x2 - currentArrowPosition.value.x1;
      const dy = currentArrowPosition.value.y2 - currentArrowPosition.value.y1;
      const length = Math.sqrt(dx * dx + dy * dy);
      const angle = Math.atan2(dy, dx);

      return {
        position: 'absolute',
        left: `${currentArrowPosition.value.x1}px`,
        top: `${currentArrowPosition.value.y1}px`,
        width: `${length}px`,
        height: '1px',
        transform: `rotate(${angle}rad)`,
        transformOrigin: '0 0',
        background: '#fff',
      } as { [key: string]: string };
    }

    return {};
  });



/* -------------------------------------------------------------------------- */
/*                                 Drag system                                */
/* -------------------------------------------------------------------------- */


function updateArrowPosition(firstBlock: any, secondBlock: any) {
    if (firstBlock && secondBlock) {
        currentArrowPosition.value = {
            x1: firstBlock.position.x,
            y1: firstBlock.position.y,
            x2: secondBlock.position.x,
            y2: secondBlock.position.y
        }

        tempArrowPosition.value = {
            x: (currentArrowPosition.value.x1 + currentArrowPosition.value.x2) / 2,
            y: (currentArrowPosition.value.y1 + currentArrowPosition.value.y2) / 2
        }
    }
}


let isDragging = false;


function dragStart(event: MouseEvent) {
    isDragging = true;
};



function handleDrag(event: MouseEvent) {
    if (currentArrow) {
        const firstBlock = exportedBlockData.getBlockById(currentArrow.firstBlock);
        const secondBlock = exportedBlockData.getBlockById(currentArrow.secondBlock);
        updateArrowPosition(firstBlock, secondBlock)
    }
};


function dragEnd() {
    isDragging = false;
};



onMounted(() => {
    document.body.addEventListener('mousedown', dragStart);
    document.body.addEventListener('mouseup', dragEnd);
    document.body.addEventListener('mousemove', handleDrag);
});


onUnmounted(() => {
    document.body.removeEventListener('mousedown', dragStart);
    document.body.removeEventListener('mouseup', dragEnd);
    document.body.removeEventListener('mousemove', handleDrag);
});
</script>




<style scoped>
.arrow {
    position: absolute;
    border: 1px solid #555;
    user-select: none;
    pointer-events: none;
}

.arrow::before {
    content: '';
    position: absolute;
    width: 10px;
    /* Largeur de la flèche */
    height: 10px;
    /* Hauteur de la flèche */
    background-color: #fff;
    clip-path: polygon(50% 0%, 0% 100%, 100% 100%);
    top: 50%;
    /* Ajustez la position verticale de la flèche selon vos besoins */
    left: 100%;
    /* Ajustez la position horizontale de la flèche selon vos besoins */
    transform: translate(-50%, -50%) rotate(90deg);
    /* Rotation de la flèche */
}
</style>
