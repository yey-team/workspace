<template>
    <div class="work-plan" @mousedown="startDrag" @mousemove="handleDrag" @mouseup="stopDrag" @mouseleave="stopDrag" @wheel.prevent="handleZoom" v-on:click.right="openMenu($event)" v-on:click.left="closeMenu()">
      <div class="content" :style="{ transform: `translate(${translate.x}px, ${translate.y}px) scale(${scale})` }">
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

        <button @click="() => {newBlock('text'); updatePosition();}">
          Ajouter un bloc de text
        </button>
        <button @click="newBlock('image')">
          Ajouter un bloc d'image
        </button>
        
        
        <button @click="goToCoords(500,500)">
          Aller en 500x500
        </button>

        <button @click="goToBlock('0')">
          Aller à la boite n°1
        </button>
        
        
        <Arrow :startPoint="allBlock[0].position" :endPoint="allBlock[1].position"/>
      </div>


      <Menu :style="{ top: `${yPointMenu}px` , left: `${xPointMenu}px`, transform: `scale(${xScaleMenu}, ${yScaleMenu})` }" :configMenu="configMenu" />


    </div>
  </template>
  
  
  <script setup>
    import { ref } from 'vue';

    //import Block from './Block.vue';
    import Menu from './Menu/MenuContainer.vue';
    import Block from './Block.vue';
    import Arrow from './Arrow.vue';


    // const blocks = getBlocks()
    import { blocks, newBlock } from '@/helpers/blockHelper.js';
    import {configsMenu} from '@/helpers/configMenu.js'

    //Import store
    import { Store } from '@/store.js'


    
    
    const allBlock = ref(blocks)
    
    const xPointMenu = ref(0)
    const yPointMenu = ref(0)
    let configMenu = ref([])
    const xScaleMenu = ref(0)
    const yScaleMenu = ref(0)

    
    
    const isDragging = ref(false);
    
    const lastMousePosition = ref({x: 0,y: 0});
    
    const translate = ref({x: 0,y: 0});
    
    const scale = ref(1);
    
    const velocity = ref({x: 0,y: 0});
    



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
      translate.value = {x, y}
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
    };
  
  
    function stopDrag() {
      isDragging.value = false;
      updatePosition();
    };
    
  
  
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
      translate.value.x = (translate.value.x - centerX) * (clampedScale / scale.value) + centerX;
      translate.value.y = (translate.value.y - centerY) * (clampedScale / scale.value) + centerY;

      // Mettre à jour l'échelle
      scale.value = clampedScale;
    };



    /* -------------------------------------------------------------------------- */
    /*                                 Right Click                                */
    /* -------------------------------------------------------------------------- */

    function openMenu(event){

      //? Remove basic menu  
      event.preventDefault()

      xScaleMenu.value = 1
      yScaleMenu.value = 1

      // console.log(event)
      this.xPointMenu = event.clientX
      this.yPointMenu = event.clientY

      //save position of the mouse in store
      Store.mouseX = event.clientX
      Store.mouseY = event.clientY

      configMenu = configsMenu.mainMenu;

      // blockManager.newBlock("image")
    }

    function closeMenu(){
      xScaleMenu.value = 0
      yScaleMenu.value = 0
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