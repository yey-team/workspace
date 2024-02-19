<template>
  <div class="arrow" :style="{ top: `${tempArrowPosition.y}px`, left: `${tempArrowPosition.x}px` }">
    
  </div>
</template>




<script setup lang="ts">

  /* -------------------------------------------------------------------------- */
  /*                                   Imports                                  */
  /* -------------------------------------------------------------------------- */

  import { ref, onMounted, onUnmounted } from 'vue';
  import exportedArrowsData from '@/helpers/arrowsHelper';
  import exportedBlockData from '@/helpers/blockHelper';
  import { useCanvasStore } from '@/helpers/store';

  /* -------------------------------------------------------------------------- */
  /*                                  Variables                                 */
  /* -------------------------------------------------------------------------- */

  const props = defineProps(["id"]) 

  const currentArrow = exportedArrowsData.getArrowById(props.id);
  
  const tempArrowPosition: any = ref(undefined)
  const currentArrowPosition: any = ref(undefined)

  const firstBlockPosition = ref({x:0,y:0})
  const secondBlockPosition = ref({x:0,y:0})

  if (currentArrow){
    
    const firstBlock = exportedBlockData.getBlockById(currentArrow.firstBlock);
    const secondBlock = exportedBlockData.getBlockById(currentArrow.secondBlock);
    
    if (firstBlock && secondBlock){
      firstBlockPosition.value = firstBlock.position
      secondBlockPosition.value = secondBlock.position
    }

    updateArrowPosition(firstBlock, secondBlock)
  }
 


/* -------------------------------------------------------------------------- */
/*                                 Drag system                                */
/* -------------------------------------------------------------------------- */


  function updateArrowPosition(firstBlock:any, secondBlock:any){
    if (firstBlock && secondBlock){
      currentArrowPosition.value = {
        x1: firstBlock.position.x, 
        y1:firstBlock.position.y, 
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
    if (currentArrow){
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
}
</style>
