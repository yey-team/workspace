<template>
  <div
    class="work-plan"
    @mousedown="startDrag"
    @mousemove="handleDrag"
    @mouseup="stopDrag"
    @mouseleave="stopDrag"
    @wheel.prevent="handleZoom"
  >

    <div class="content" :style="{ transform: `translate(${translate.x}px, ${translate.y}px) scale(${scale})` }">
      <!-- Contenu de votre plan de travail -->

      <TestBox/>

    </div>
  </div>
</template>


<script setup>
import { ref } from 'vue';
import TestBox from './components/TestBox.vue';

const isDragging = ref(false);
const lastMousePosition = ref({ x: 0, y: 0 });
const translate = ref({ x: 0, y: 0 });
const scale = ref(1);
const velocity = ref({ x: 0, y: 0 });

const startDrag = (event) => {
  if (event.button === 1) {
    isDragging.value = true;
    lastMousePosition.value = { x: event.clientX, y: event.clientY };
    velocity.value = { x: 0, y: 0 }; // Réinitialiser la vitesse lors du début du glissement
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

    lastMousePosition.value = { x: event.clientX, y: event.clientY };
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

  if (delta > 0) {
    scale.value -= zoomSpeed;
  } else {
    scale.value += zoomSpeed;
  }
};
</script>

<style scoped>
.work-plan {
  width: 100%;
  height: 100%;
  overflow: hidden;
  position: relative;
  transition: transform 0.2s ease-out; /* Ajoutez une transition pour une expérience plus fluide */
  cursor: grab; /* Changement du curseur pour indiquer que le déplacement est activé */
}

.work-plan:active {
  cursor: grabbing; /* Changement du curseur lorsque le clic est enfoncé */
}


.content {
  position: absolute;
  top: 0;
  left: 0;
}
</style>