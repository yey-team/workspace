<template>
  <div
    class="container"
    :style="{ top: boxPosition.y + 'px', left: boxPosition.x + 'px'}"
    @mousedown="startDrag"
    @mouseup="stopDrag"
  >
    <!-- Votre contenu personnalisé -->
    {{ content }}

    <br>

    <component :is="selectedComponent" />

  </div>
</template>

<script setup>
import { ref, shallowRef, onMounted, onBeforeUnmount } from 'vue';
import TextBloc from './Blocs/TextBloc.vue';
import ImageBloc from './Blocs/ImageBloc.vue';

let magnetEffectSize = 20;

const props = defineProps(['position', 'content', 'scale', 'type']);
const emits = defineEmits();


/* -------------------------------------------------------------------------- */
/*                             Variable Component                             */
/* -------------------------------------------------------------------------- */

const listOfComponents = shallowRef({"text": TextBloc, 
                                      "image": ImageBloc
                                    })

const selectedComponent = shallowRef(null);

// Fonction pour changer le composant sélectionné
const selectComponent = () => {
  // var randomComponent = listOfComponents.value[Math.floor(Math.random()*listOfComponents.value.length)];
  console.log(props.type)
  console.log(listOfComponents.value[props.type]);


  selectedComponent.value = listOfComponents.value[props.type];
};


/* -------------------------------------------------------------------------- */
/*                               Dragging system                              */
/* -------------------------------------------------------------------------- */


const isDragging = ref(false);
const lastMousePosition = ref({ x: 0, y: 0 });
const boxPosition = ref({ x: props.position.x, y: props.position.y });

onMounted(() => {
  document.addEventListener('mousemove', handleDrag);
  selectComponent()
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
  min-height: 100px;

  cursor: grab;
  transition: transform 0.2s ease-out;
}

.container:active {
  cursor: grabbing;
}
</style>
