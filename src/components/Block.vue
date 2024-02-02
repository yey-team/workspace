<template>
  <div  class="block" 
        :style="{ 
                  top: `${testBlockPosition.y}px`, 
                  left: `${testBlockPosition.x}px` }">

    {{ testBlock.type }}
  
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { blocks } from '@/helpers/blockHelper.js';

const localBlocks = JSON.parse(JSON.stringify(blocks));

let testBlock = localBlocks[0];
let testBlockPosition = ref({ x: 0, y: 0 });

let isDragging = false;
let startDrag = { x: 0, y: 0 };


document.body.addEventListener("mousedown", dragStart)
document.body.addEventListener("mouseup", dragEnd)
document.body.addEventListener("mousemove", handleDrag)


function dragStart(event) {
  let clickOnBlock = event.target.classList.contains('block')
  if (clickOnBlock) {
    isDragging = true;
    startDrag = { x: event.clientX - testBlockPosition.value.x, y: event.clientY - testBlockPosition.value.y };
  }
}

function handleDrag(event) {

  if (isDragging) {
    testBlockPosition.value.x = event.clientX - startDrag.x;
    testBlockPosition.value.y = event.clientY - startDrag.y;
  }
}

function dragEnd() {
    isDragging = false;
}
</script>

<style scoped>
.block {
  padding: 1rem;
  background: #fff2;
  position: absolute;
}
</style>
