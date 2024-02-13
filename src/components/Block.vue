<template>
  <div class="block" :style="{ top: `${testBlockPosition.y}px`, left: `${testBlockPosition.x}px` }">
    {{ testBlock.type }}
  </div>
</template>




<script setup lang="ts">
  import { ref, onMounted, onUnmounted } from 'vue';
  import exportedData from '@/helpers/blockHelper';
  import { useCanvasStore } from '@/helpers/store';

  const canvasStore = useCanvasStore()

  const blocks = exportedData.blocks;
  const localBlocks = JSON.parse(JSON.stringify(blocks));
  let testBlock = ref(localBlocks[0]);
  let testBlockPosition = ref({
    x: 0,
    y: 0
  });


  /* -------------------------------------------------------------------------- */
  /*                                 Drag system                                */
  /* -------------------------------------------------------------------------- */


  let isDragging = false;
  
  let startDrag = {
    x: 0,
    y: 0
  };

  const lastMousePosition = ref({ 
    x: 0, 
    y: 0 
  });


  const dragStart = (event: MouseEvent) => {
    const clickOnBlock = (event.target as HTMLElement).classList.contains('block');
    if (clickOnBlock) {
      isDragging = true;
      startDrag = {
        x: event.clientX - testBlockPosition.value.x,
        y: event.clientY - testBlockPosition.value.y
      };
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
