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
  
  const testBlock = ref(getBlockById(localBlocks, props.id));
  const testBlockPosition: any = ref(undefined)
  if (testBlock.value){
    testBlockPosition.value = testBlock.value.position
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


  const isClickOnBlockClass = (target: HTMLElement) => target.classList.contains('block');
  const isClickOnBlockID = (target: HTMLElement) => testBlock.value?.id && target.classList.contains(testBlock.value.id);

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


      testBlockPosition.value = {
        x: testBlockPosition.value.x + (deltaX * speedFactor),
        y: testBlockPosition.value.y + (deltaY * speedFactor),
      };


      lastMousePosition.value = { x: event.clientX, y: event.clientY };
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
  }
</style>
