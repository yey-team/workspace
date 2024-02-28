<template>
  <div v-if="currentBlock" class="block" :style="{ top: `${currentBlockPosition.y}px`, left: `${currentBlockPosition.x}px` }">
    {{ currentBlock.type }}
  </div>
</template>




<script setup lang="ts">
  import { ref, onMounted, onUnmounted } from 'vue';
  import { useBlockStore } from '@/helpers/blockHelper';
  import { useCanvasStore } from '@/helpers/store';

  const canvasStore = useCanvasStore()
  const props = defineProps(["id"]) 

  const exportedBlockData = useBlockStore()

  const blocks = exportedBlockData.blocks;
  const localBlocks = JSON.parse(JSON.stringify(blocks));
  
  const currentBlock = ref(getBlockById(localBlocks, props.id));
  const currentBlockPosition: any = ref(undefined)
  if (currentBlock.value){
    currentBlockPosition.value = currentBlock.value.position
  }



  /* -------------------------------------------------------------------------- */
  /*                                Define blocks                               */
  /* -------------------------------------------------------------------------- */

  interface Block {
    id: string;
    position: { x: number; y: number };
    content: string;
    type: string;
    links: string[];
  }

  function getBlockById(blocks: Block[], id: string): Block | undefined {
    return blocks.find(block => block.id === id);
  }


  /* -------------------------------------------------------------------------- */
  /*                                 Drag system                                */
  /* -------------------------------------------------------------------------- */


  let isDragging = false;


  const isClickOnBlockClass = (target: HTMLElement) => target.classList.contains('block');
  const isClickOnBlockID = (target: HTMLElement) => currentBlock.value?.id && target.classList.contains(currentBlock.value.id);

  function dragStart(event: MouseEvent) {
    const clickOnBlockClass = isClickOnBlockClass(event.target as HTMLElement);
    const clickOnBlockID = isClickOnBlockID(event.target as HTMLElement);

    if (clickOnBlockClass && clickOnBlockID) {
      isDragging = true;
    }
  };



  function handleDrag(event: MouseEvent) {
    if (isDragging) {
      const deltaX = event.movementX;
      const deltaY = event.movementY;

      const speedFactor = 1 / canvasStore.zoom;

      if (currentBlock.value){
        currentBlock.value.position = currentBlockPosition.value;
        exportedBlockData.updateSingleBlock(currentBlock.value.id, currentBlock.value)
      }
      
      currentBlockPosition.value = {
        x: currentBlockPosition.value.x + (deltaX * speedFactor),
        y: currentBlockPosition.value.y + (deltaY * speedFactor),
      };
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
  .block {
    padding: 1rem;
    background: #fff2;
    position: absolute;
    user-select: none;
  }
</style>
