<template>
  <div class="work-plan" @mousedown="startDrag" @mousemove="handleDrag" @mouseup="stopDrag" @mouseleave="stopDrag" @wheel.prevent="handleZoom">
    <div class="content" :style="{ transform: `translate(${translate.x}px, ${translate.y}px) scale(${scale})` }">
      <!-- Contenu de votre plan de travail -->
      <Block />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';

// Importer Block avec la bonne extension (.vue)
import Block from './Block.vue';

const isDragging = ref(false);
const lastMousePosition = ref({ x: 0, y: 0 });
const translate = ref({ x: 0, y: 0 });
const scale = ref(1);

const velocity = ref({ x: 0, y: 0 });

/* -------------------------------------------------------------------------- */
/*                                 Système de glissement                       */
/* -------------------------------------------------------------------------- */

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

  scale.value = clampedScale;
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
