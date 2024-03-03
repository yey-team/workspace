<template>
  <div v-if="currentBlock" class="block" :style="{ top: `${currentBlockPosition.y}px`, left: `${currentBlockPosition.x}px` }" @click="sendToLinkedBlocks">
    {{ currentBlock.content }}
    {{ currentBlock.type }}
  </div>
</template>




<script setup lang="ts">
  import { ref, onMounted, onUnmounted } from 'vue';
  import { useBlockStore } from '@/helpers/blockHelper';
  import { useArrowStore } from '@/helpers/arrowsHelper';
  import { useCanvasStore } from '@/helpers/store';

  const canvasStore = useCanvasStore();
  const props = defineProps(["id"]);

  const exportedBlockData = useBlockStore();
  const exportedArrowsData = useArrowStore();

  const blocks = exportedBlockData.blocks;
  
  const currentBlock = ref(getBlockById(blocks, props.id));
  const currentBlockPosition: any = ref(undefined);
  if (currentBlock.value){
    currentBlockPosition.value = currentBlock.value.position;
  }




  function test(){
    console.log("↓↓↓↓↓↓↓↓↓↓")
    console.log("Execution de la fonction test() du block : " + currentBlock.value?.id)
    console.log(currentBlock.value?.content)
    console.log("↑↑↑↑↑↑↑↑↑↑")

    console.log(" ")
  }


  


  function sendToLinkedBlocks(){
    if (currentBlock.value){
      const targetBlocks = exportedArrowsData.getTargetsByOrigin(currentBlock.value.id);

      if (targetBlocks.length > 0){
        targetBlocks.forEach(targetBlockID => {
          const block = exportedBlockData.getBlockById(targetBlockID);
          if (block){
            block.callback();
          }
        });
      }

    }
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
    callback: CallableFunction;
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
        exportedBlockData.updateSingleBlock(currentBlock.value.id, currentBlock.value);
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


    if (currentBlock.value){
      exportedBlockData.setBlockCallback(currentBlock.value.id, test);

      currentBlock.value = getBlockById(blocks, props.id);
    }
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
