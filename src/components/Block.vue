<template>
  <div
    class="container"
    :style="{ top: boxPosition.y + 'px', left: boxPosition.x + 'px', zIndex: index}"
    @mousedown="startDrag"
    @mouseup="() => { stopDrag(); $emit('onUpdate', {id: props.id, position: {x:boxPosition.x, y:boxPosition.y}}); }"
  >
    <!-- Votre contenu personnalisé -->
    {{ content }}

    <br>

    <component :is="selectedComponent" @selected="setBlocTop" @unselected="unsetBlocTop"/>

  </div>
</template>

<script setup>
import { ref, shallowRef, onMounted, onBeforeUnmount } from 'vue';
import TextBlock from './Blocks/TextBlock.vue';
import ImageBlock from './Blocks/ImageBlock.vue';

let magnetEffectSize = 20;

const props = defineProps(['id', 'position', 'content', 'scale', 'type']);
const boxPosition = ref({ x: props.position.x, y: props.position.y });

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

function setBlocTop(){
  index.value = 10;
}

function unsetBlocTop(){
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
    setBlocTop()

    isDragging.value = true;
    lastMousePosition.value = { x: event.clientX, y: event.clientY };
  }
};

function updatePosition(newPosition) {
  boxPosition.value = newPosition;
};

function handleDrag(event) {
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

function stopDrag(){
  if (isDragging.value) {
    // Is not dragging
    unsetBlocTop()

    isDragging.value = false;

    const newBoxPosition = {
      x: Math.round(boxPosition.value.x / magnetEffectSize ) * magnetEffectSize,
      y: Math.round(boxPosition.value.y / magnetEffectSize ) * magnetEffectSize,
    }

    updatePosition(newBoxPosition)
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
