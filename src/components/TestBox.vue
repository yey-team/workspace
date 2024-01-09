<template>
  <div
    class="container"
    :style="{ top: boxPosition.y + 'px', left: boxPosition.x + 'px', transform: `scale(${zoom})` }"
    @mousedown="startDrag"
    @mousemove="handleDrag"
    @mouseup="stopDrag"
    @wheel.prevent="handleZoom"
  >
    <!-- Votre contenu personnalisé -->
    {{ content }}
  </div>
</template>

<script setup>
import { ref, defineProps, defineEmits } from 'vue';

const props = defineProps(['position', 'content']);
const emits = defineEmits();

const isDragging = ref(false);
const lastMousePosition = ref({ x: 0, y: 0 });
const zoom = ref(1);
const boxPosition = ref({ x: props.position.x, y: props.position.y });

const startDrag = (event) => {
  if (event.button === 0) {
    isDragging.value = true;
    lastMousePosition.value = { x: event.clientX, y: event.clientY };
  }
};

const handleDrag = (event) => {
  if (isDragging.value) {
    const deltaX = (event.clientX - lastMousePosition.value.x) / zoom.value;
    const deltaY = (event.clientY - lastMousePosition.value.y) / zoom.value;

    const newBoxPosition = {
      x: boxPosition.value.x + deltaX,
      y: boxPosition.value.y + deltaY,
    };

    // Émettre les coordonnées absolues de la boîte
    emits('drag', { x: newBoxPosition.x, y: newBoxPosition.y });

    lastMousePosition.value = { x: event.clientX, y: event.clientY };
  }
};

const stopDrag = () => {
  isDragging.value = false;
  emits('stop-drag');

};

const handleZoom = (event) => {
  const delta = event.deltaY;
  const zoomSpeed = 0.02;

  if (delta > 0) {
    zoom.value -= zoomSpeed;
  } else {
    zoom.value += zoomSpeed;
  }
};
</script>

<style scoped>
.container {
  position: absolute;
  background-color: var(--main-div-color);
  padding: 1rem;
  border-radius: var(--large-border-radius);
  border: 1px solid var(--border-color);
  box-shadow: 0px 5px 5px 0px rgba(0, 0, 0, 0.25);
  width: 200px;
  height: 100px;
  cursor: grab;
  transition: transform 0.2s ease-out;
}

.container:active {
  cursor: grabbing;
}
</style>
