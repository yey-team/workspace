<template>
  <div v-if="testBlock" class="block" :style="{ top: `${testBlockPosition.y}px`, left: `${testBlockPosition.x}px` }">
    {{ testBlock?.type }}
  </div>
</template>




<script setup lang="ts">
  import { ref, onMounted, onUnmounted } from 'vue';
  import exportedData from '@/helpers/blockHelper';
  import { useCanvasStore } from '@/helpers/store';

  const canvasStore = useCanvasStore()
  const props = defineProps(["id"]) 

  const blocks = exportedData.blocks;
  const localBlocks = JSON.parse(JSON.stringify(blocks));
  
  let testBlock = ref(getBlockById(localBlocks, props.id));
  let testBlockPosition: any = undefined
  if (testBlock.value){
    testBlockPosition = testBlock.value.position
  }


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


  const lastMousePosition = ref({ 
    x: 0, 
    y: 0 
  });


  const dragStart = (event: MouseEvent) => {
    const clickOnBlock = (event.target as HTMLElement).classList.contains('block');
    if (clickOnBlock) {
      isDragging = true;
    }
  };


  const handleDrag = (event: MouseEvent) => {
    if (isDragging) {
      const deltaX = event.movementX;
      const deltaY = event.movementY;

      const speedFactor = 1 / canvasStore.zoom;

      testBlockPosition.value = {
        x: testBlockPosition.value.x + (deltaX * speedFactor),
        y: testBlockPosition.value.y + (deltaY * speedFactor),
      };

      lastMousePosition.value = { x: event.clientX, y: event.clientY };
    }
  };


  const dragEnd = () => {
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
  }
</style>
