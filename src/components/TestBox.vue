<template>
  <div
    class="container"
    :style="{ top: boxPosition.y + 'px', left: boxPosition.x + 'px'}"
    @mousedown="startDrag"
    @mouseup="stopDrag"
    @mouseleave="stopDrag"
  >
    <!-- Votre contenu personnalisé -->
    {{ content }}
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';

const props = defineProps(['position', 'content', 'scale']);
const emits = defineEmits();

const isDragging = ref(false);
const lastMousePosition = ref({ x: 0, y: 0 });
const boxPosition = ref({ x: props.position.x, y: props.position.y });


let gridMagnetEffectSize = 10;


onMounted(() => {
  document.addEventListener('mousemove', handleDrag);
});

onBeforeUnmount(() => {
  document.removeEventListener('mousemove', handleDrag);
});

const startDrag = (event) => {
  if (event.button === 0) {
    isDragging.value = true;
    lastMousePosition.value = { x: event.clientX, y: event.clientY };
  }
};

const updatePosition = (newPosition) => {
  boxPosition.value = newPosition;
};

const handleDrag = (event) => {
  if (isDragging.value) {
    const deltaX = event.movementX || (event.clientX - lastMousePosition.value.x);
    const deltaY = event.movementY || (event.clientY - lastMousePosition.value.y);

    const speedFactor = 1 / props.scale;

    const newBoxPosition = {
      x: boxPosition.value.x + (deltaX * speedFactor),
      y: boxPosition.value.y + (deltaY * speedFactor),
    };

    updatePosition(newBoxPosition);

    lastMousePosition.value = { x: event.clientX, y: event.clientY };
  }
};

const stopDrag = () => {
  if (isDragging.value) {
    isDragging.value = false;

    const newBoxPosition = {
      x: Math.round(boxPosition.value.x / gridMagnetEffectSize) * gridMagnetEffectSize,
      y: Math.round(boxPosition.value.y / gridMagnetEffectSize) * gridMagnetEffectSize,
    };

    updatePosition(newBoxPosition);
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
