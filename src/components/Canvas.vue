<template>
  <div class="work-plan" @mousedown="startDrag" @mousemove="handleDrag" @mouseup="stopDrag" @mouseleave="stopDrag" @wheel.prevent="handleZoom">
    <div class="content" :style="{ transform: `translate(${translate.x}px, ${translate.y}px) scale(${canvasStore.zoom})` }">
      <Block 
        v-for="block in exportedBlockData.blocks"
        :id="block.id"
        :class="block.id"
        />
        
        
        <button @click="goToCoords(500,500)">
          Aller en 500x500
        </button>

        <button @click="goToBlock('0')">
          Aller à la boite n°1
        </button>

        <Arrow 
        v-for="arrow in exportedArrowsData.arrows"
        :id="arrow.id"
        />
      </div>


      <Menu 
        v-for="(currentMenu, index) in storage.menus"
        :key="index"
        :configMenu="currentMenu.config" 
        :style="{ top: `${currentMenu.position.y}px` , left: `${currentMenu.position.x}px`, transform: `scale(${currentMenu.scale.x}, ${currentMenu.scale.y})` }" 
      />

    </div>
  </template>
  
  
  <script setup>
    import { ref } from 'vue';


<script setup lang="ts">
  import { ref } from 'vue';
  import Block from './Block.vue';
  import Arrow from './Arrow.vue';
  import { useCanvasStore } from '@/helpers/store';
  import exportedBlockData from '@/helpers/blockHelper';
  import exportedArrowsData from '@/helpers/arrowsHelper';


  const isDragging = ref(false);
  const lastMousePosition = ref({
    x: 0,
    y: 0
  });
  const translate = ref({
    x: 0,
    y: 0
  });

  const canvasStore = useCanvasStore()

  const velocity = ref({
    x: 0,
    y: 0
  });




    // ↓↓ Temporaire ↓↓
    exportedBlockData.newBlock("image", 50, 50)
    exportedBlockData.newBlock("text", 200, 100)
    exportedArrowsData.newArrow(exportedBlockData.blocks[0].id, exportedBlockData.blocks[1].id)

    const idWorkPlan = ref("work-plan")


  /* -------------------------------------------------------------------------- */
  /*                                 Système de glissement                      */
  /* -------------------------------------------------------------------------- */

    function goToCoords(x=0, y=0) {
      x = -x * scale.value;
      y = -y * scale.value;
      storage.coordCanvas = {x, y}
    }
    
    function goToBlock(blockId) {
      const block = getBlockByID(blockId)
      goToCoords(block.position.x,block.position.y);
    }
 

    function updateBlock(attributes) {
      const block = getBlockByID(attributes.id)
      block.position = attributes.position
    }


  function startDrag(event: MouseEvent) {
    if (event.button === 1) {
      isDragging.value = true;
      lastMousePosition.value = {
        x: event.clientX,
        y: event.clientY
      };
      velocity.value = {
        x: 0,
        y: 0
      }; // Réinitialiser la vitesse lors du début du glissement
    }
  }


  function handleDrag(event: MouseEvent) {
    if (isDragging.value) {
      const deltaX = event.clientX - lastMousePosition.value.x;
      const deltaY = event.clientY - lastMousePosition.value.y;
      translate.value.x += deltaX;
      translate.value.y += deltaY;
      // Calcul de la vitesse normalisée
      velocity.value.x = deltaX / 2;
      velocity.value.y = deltaY / 2;
      lastMousePosition.value = {
        x: event.clientX,
        y: event.clientY
      };
    }
  }


  function stopDrag() {
    isDragging.value = false;
    updatePosition();
  }


  function updatePosition() {
    const glidingFactor = 0.93; // Facteur de glissement
    function update() {
      translate.value.x += velocity.value.x;
      translate.value.y += velocity.value.y;
      velocity.value.x *= glidingFactor;
      velocity.value.y *= glidingFactor;
      if (Math.abs(velocity.value.x) > 0.01 || Math.abs(velocity.value.y) > 0.01) {
        requestAnimationFrame(update);
      }
    }
    update();
  }


  function handleZoom(event: WheelEvent) {


    const delta = event.deltaY;
    const zoomSpeed = 0.02;
    // Position du centre de l'écran
    // const centerX = window.innerWidth / 2;
    // const centerY = window.innerHeight / 2;
    const centerX = event.clientX;
    const centerY = event.clientY;
    // Définir des valeurs minimales et maximales pour le zoom
    const minScale = 0.1;
    const maxScale = 2.0;
    // Calculer le nouveau niveau de zoom
    const newScale = canvasStore.zoom + (delta > 0 ? -zoomSpeed : zoomSpeed);
    // Limiter le niveau de zoom dans les limites spécifiées
    const clampedScale = Math.max(minScale, Math.min(maxScale, newScale));

    // Ajuster la translation pour centrer le zoom au milieu de l'écran
    translate.value.x = (translate.value.x - centerX) * (clampedScale / canvasStore.zoom) + centerX;
    translate.value.y = (translate.value.y - centerY) * (clampedScale / canvasStore.zoom) + centerY;
    canvasStore.zoom = clampedScale;
  }

  /* -------------------------------------------------------------------------- */
  /*                                 Right Click                                */
  /* -------------------------------------------------------------------------- */
  function openMenu(event){

    //? Remove basic menu  
    event.preventDefault()

    // clear all existing menu
    storage.menus = []

    //save position of the mouse in store
    storage.mouseX = event.clientX
    storage.mouseY = event.clientY
    // console.log("toto", storage.coordCanvas.x)

    // instance new menu
    storage.menus.push({
      position: {x: event.clientX,y: event.clientY},
      scale: {x: 1,y: 1},
      config: configsMenu.mainMenu,
    })

    // blockManager.newBlock("image")
  }

  function closeMenu(event){
    // clear all menu
    // storage.menus = []
    // test if click is on workplan id
    // console.log(event.srcElement.id, idWorkPlan.value)
    if (event.srcElement.id === idWorkPlan.value) {
      storage.menus = []
    }
  }
</script>




<style scoped>
  .work-plan {
    width: 100%;
    height: 100%;
    overflow: hidden;
    position: relative;
    transition: transform 0.2s ease-out;
    cursor: grab;
  }
  .work-plan:active {
    cursor: grabbing;
  }
  .content {
    position: absolute;
    top: 0;
    left: 0;
  }
</style>
