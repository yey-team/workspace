<template>
    <div class="work-plan" :id="idWorkPlan" @mousedown="startDrag" @mousemove="handleDrag" @mouseup="stopDrag" @mouseleave="stopDrag" @wheel.prevent="handleZoom" v-on:click.right="openMenu($event)" v-on:click.left="closeMenu($event)">
      <div class="content" :style="{ transform: `translate(${storage.coordCanvas.x}px, ${storage.coordCanvas.y}px) scale(${scale})` }">
        <!-- Contenu de votre plan de travail -->
        <Block
          v-for="(block, index) in allBlock"
          :id="block.id"
          :position="block.position"
          :content="block.content"
          :scale="scale"
          :type="block.type"
          @onUpdate="updateBlock"
        />
        
        
        <button @click="goToCoords(500,500)">
          Aller en 500x500
        </button>

        <button @click="goToBlock('0')">
          Aller à la boite n°1
        </button>
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

    //import Block from './Block.vue';
    import Menu from './Menu/MenuContainer.vue';
    
    import Block from './Block.vue';

    // const blocks = getBlocks()
    import { blocks, newBlock } from '@/helpers/blockHelper.js';
    import {configsMenu} from '@/helpers/configMenu.js'

    //Import store
    import { Store } from '@/store.js'

    const storage = Store()

    const allBlock = ref(blocks)


    const isDragging = ref(false);

    const lastMousePosition = ref({x: 0,y: 0});
  
    // const translate = ref({x: 0,y: 0});
  
    const scale = ref(1);
  
    const velocity = ref({x: 0,y: 0});
  

    const idWorkPlan = ref("work-plan")


    /* -------------------------------------------------------------------------- */
    /*                                Move to view                                */
    /* -------------------------------------------------------------------------- */


    function getBlockByID(id){

      const blockIndex = allBlock.value.findIndex(block => block.id === id);
      return allBlock.value[blockIndex];
    }


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


    /* -------------------------------------------------------------------------- */
    /*                                 Drag system                                */
    /* -------------------------------------------------------------------------- */

    function startDrag(event) {
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
    };
  
  
    function handleDrag(event) {
      if (isDragging.value) {
        const deltaX = event.clientX - lastMousePosition.value.x;
        const deltaY = event.clientY - lastMousePosition.value.y;
        // translate.value.x += deltaX;
        // translate.value.y += deltaY;
        storage.coordCanvas.x += deltaX
        storage.coordCanvas.y += deltaY;

        // Calcul de la vitesse normalisée
        velocity.value.x = deltaX / 2;
        velocity.value.y = deltaY / 2;
        lastMousePosition.value = {
          x: event.clientX,
          y: event.clientY
        };
      }
    };
  
  
    function stopDrag() {
      isDragging.value = false;
      updatePosition();
    };
    
  
  
    function updatePosition() {
      const glidingFactor = 0.93; // Facteur de glissement
      function update() {
        storage.coordCanvas.x += velocity.value.x;
        storage.coordCanvas.y += velocity.value.y;

        velocity.value.x *= glidingFactor;
        velocity.value.y *= glidingFactor;
        if (Math.abs(velocity.value.x) > 0.01 || Math.abs(velocity.value.y) > 0.01) {
          requestAnimationFrame(update);
        }
      };
      update();
    };
  
  
    function handleZoom(event) {
      const delta = event.deltaY;
      const zoomSpeed = 0.02;

      // Position du centre de l'écran
      const centerX = window.innerWidth / 2;
      const centerY = window.innerHeight / 2;

      // Définir des valeurs minimales et maximales pour le zoom
      const minScale = 0.1;
      const maxScale = 2.0;

      // Calculer le nouveau niveau de zoom
      const newScale = scale.value + (delta > 0 ? -zoomSpeed : zoomSpeed);

      // Limiter le niveau de zoom dans les limites spécifiées
      const clampedScale = Math.max(minScale, Math.min(maxScale, newScale));

      // Ajuster la translation pour centrer le zoom au milieu de l'écran
      // translate.value.x = (translate.value.x - centerX) * (clampedScale / scale.value) + centerX;
      // translate.value.y = (translate.value.y - centerY) * (clampedScale / scale.value) + centerY;

      storage.coordCanvas.x = (storage.coordCanvas.x - centerX) * (clampedScale / scale.value) + centerX;
      storage.coordCanvas.y = (storage.coordCanvas.y - centerY) * (clampedScale / scale.value) + centerY;

      // Mettre à jour l'échelle
      scale.value = clampedScale;
    };



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
      /* Ajoutez une transition pour une expérience plus fluide */
      cursor: grab;
      /* Changement du curseur pour indiquer que le déplacement est activé */
    }
    .work-plan:active {
      cursor: grabbing;
      /* Changement du curseur lorsque le clic est enfoncé */
    }
    .content {
      position: absolute;
      top: 0;
      left: 0;
    }
  </style>