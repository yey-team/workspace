<template>
  <div
    class="container"
    :style="{ top: blockPosition.y + 'px', left: blockPosition.x + 'px', zIndex: index}"
    @mousedown="startDrag"
    @mouseup="() => { stopDrag(); $emit('onUpdate', {id: props.id, position: {x:blockPosition.x, y:blockPosition.y}}); }"
  >
    <!-- Votre contenu personnalisé -->
    {{ content }}

    <br>

    <component :is="selectedComponent" @selected="setBlockTop" @unselected="unsetBlockTop"/>

  </div>
</template>

<script setup>
import { ref, shallowRef, onMounted, onBeforeUnmount } from 'vue';
import TextBlock from './Blocks/TextBlock.vue';
import ImageBlock from './Blocks/ImageBlock.vue';

let magnetEffectSize = 20;

const props = defineProps(['id', 'position', 'content', 'scale', 'type']);
const blockPosition = ref({ x: props.position.x, y: props.position.y });

const emit = defineEmits()

/* -------------------------------------------------------------------------- */
/*                             Variable Component                             */
/* -------------------------------------------------------------------------- */

const listOfComponents = shallowRef({"text": TextBlock, 
                                      "image": ImageBlock
                                    })

const selectedComponent = shallowRef(null);

// Fonction pour changer le composant sélectionné
function selectComponentToCreate() {
  selectedComponent.value = listOfComponents.value[props.type];
};



/* -------------------------------------------------------------------------- */
/*                                Z Index blocks                               */
/* -------------------------------------------------------------------------- */


const index = ref(0);

function setBlockTop(){
  index.value = 10;
}

function unsetBlockTop(){
  index.value = 0;
}



/* -------------------------------------------------------------------------- */
/*                               Dragging system                              */
/* -------------------------------------------------------------------------- */


const isDragging = ref(false);
const lastMousePosition = ref({ x: 0, y: 0 });




onMounted(() => {
  document.addEventListener('mousemove', handleDrag);
  selectComponentToCreate()
});

onBeforeUnmount(() => {
  document.removeEventListener('mousemove', handleDrag);
});

function startDrag(event) {
  if (event.button === 0) {
    // Is dragging
    setBlockTop()

    isDragging.value = true;
    lastMousePosition.value = { x: event.clientX, y: event.clientY };
  }
};

function updatePosition(newPosition) {
  blockPosition.value = newPosition;
};

function handleDrag(event) {
  if (isDragging.value) {
    const deltaX = event.movementX || (event.clientX - lastMousePosition.value.x);
    const deltaY = event.movementY || (event.clientY - lastMousePosition.value.y);

    const speedFactor = 1 / props.scale;

    const newBlockPosition = {
      x: blockPosition.value.x + (deltaX * speedFactor),
      y: blockPosition.value.y + (deltaY * speedFactor),
    };

    updatePosition(newBlockPosition);

    lastMousePosition.value = { x: event.clientX, y: event.clientY };
  }
};

function stopDrag(){
  if (isDragging.value) {
    // Is not dragging
    unsetBlockTop()

    isDragging.value = false;

    const newBlockPosition = {
      x: Math.round(blockPosition.value.x / magnetEffectSize ) * magnetEffectSize,
      y: Math.round(blockPosition.value.y / magnetEffectSize ) * magnetEffectSize,
    }

    updatePosition(newBlockPosition)
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
  
  width: auto;
  height: auto;

  min-width: 200px;
  min-height: 50px;

  cursor: grab;
  transition: transform 0.2s ease-out;
}

.container:active {
  cursor: grabbing;
}
</style>
