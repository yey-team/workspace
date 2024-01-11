<template>
    <div class="work-plan" @mousedown="startDrag" @mousemove="handleDrag" @mouseup="stopDrag" @mouseleave="stopDrag" @wheel.prevent="handleZoom" v-on:click.right="openMenu($event)">
      <div class="content" :style="{ transform: `translate(${translate.x}px, ${translate.y}px) scale(${scale})` }">
        <!-- Contenu de votre plan de travail -->
        <Bloc
          v-for="(box, index) in boxes"
          :key="index"
          :position="box.position"
          :scale="scale"
          :type="box.type"
          :content="box.content"
        />

        <button @click="newBloc('text')">
          Ajouter un bloc de text
        </button>
        <button @click="newBloc('image')">
          Ajouter un bloc d'image
        </button>
      </div>


      <Menu :style="{top: `${yPointMenu}px` , left: `${xPointMenu}px`}" :configMenu="'toto'" />

    </div>
  </template>
  
  
  <script setup>
    import { ref } from 'vue';
    import { createApp } from 'vue'

    import Bloc from './Bloc.vue';
    import Menu from './Menu.vue';
    
    let xPointMenu = ref(0)
    let yPointMenu = ref(0)
    
    const isDragging = ref(false);

    const lastMousePosition = ref({x: 0,y: 0});
  
    const translate = ref({x: 0,y: 0});
  
    const scale = ref(1);
  
    const velocity = ref({x: 0,y: 0});
  
    const boxes = ref([{
                      position: {x: 50,y: 50},
                      content: 'Boîte 1',
                      type: "image"
                    },{
                      position: {x: 200,y: 100},
                      content: 'Boîte 2',
                      type: "text"
                    }
                    ]);
  
  

    const newBloc = (blocType) => {
      boxes.value.push({position: {x: 0,y: 0},
                          content: "Boîte " + (boxes.value.length + 1),
                          type: blocType
                        })
    }


    /* -------------------------------------------------------------------------- */
    /*                                 Drag system                                */
    /* -------------------------------------------------------------------------- */

    const startDrag = (event) => {
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
  
  
    const handleDrag = (event) => {
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
  
  
    const stopDrag = () => {
      isDragging.value = false;
      updatePosition();
    };
    
  
  
    const updatePosition = () => {
      const glidingFactor = 0.92; // Facteur de glissement
      const update = () => {
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
  
  
    const handleZoom = (event) => {
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

      console.log(event)
      this.xPointMenu = event.clientX
      this.yPointMenu = event.clientY
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