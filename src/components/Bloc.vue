<template>
  <!-- Conteneur principal -->
  <div
    class="container"
    :style="{ top: boxPosition.y + 'px', left: boxPosition.x + 'px'}"
    @mousedown="(event) => { startDrag(event); changeComponent(); } "
    @mouseup="stopDrag"
    @mouseleave="stopDrag"
  >
    <!-- Contenu personnalisé (point d'injection) -->
    {{ content }}

    <br>

    <!-- Composant dynamique basé sur la valeur de selectedComponent -->
    <component :is="selectedComponent"/>
  </div>
</template>

<script setup>
import { ref, shallowRef, onMounted, onBeforeUnmount, defineProps, defineEmits } from 'vue';
import TextBloc from './TextBloc.vue';
import ImageBloc from './ImageBloc.vue';

// Référence réactive pour le composant sélectionné
const selectedComponent = shallowRef(TextBloc);

// Fonction pour changer le composant sélectionné
const changeComponent = () => {
  selectedComponent.value = selectedComponent.value === TextBloc ? ImageBloc : TextBloc;
};

// Propriétés et émetteurs du composant
const props = defineProps(['position', 'content', 'scale']);
const emits = defineEmits();

// État du glisser-déposer
const isDragging = ref(false);
// Dernière position de la souris
const lastMousePosition = ref({ x: 0, y: 0 });
// Position du conteneur
const boxPosition = ref({ x: props.position.x, y: props.position.y });

// Taille de l'effet de magnétisme à la grille
let gridMagnetEffectSize = 10;

// Gestion de l'événement après le montage du composant
onMounted(() => {
  document.addEventListener('mousemove', handleDrag);
});

// Gestion de l'événement avant le démontage du composant
onBeforeUnmount(() => {
  document.removeEventListener('mousemove', handleDrag);
});

// Fonction pour démarrer le glisser-déposer
const startDrag = (event) => {
  if (event.button === 0) {
    isDragging.value = true;
    lastMousePosition.value = { x: event.clientX, y: event.clientY };
  }
};

// Fonction pour mettre à jour la position du conteneur
const updatePosition = (newPosition) => {
  boxPosition.value = newPosition;
};

// Gestion du glisser-déposer
const handleDrag = (event) => {
  if (isDragging.value) {
    const deltaX = event.movementX || (event.clientX - lastMousePosition.value.x);
    const deltaY = event.movementY || (event.clientY - lastMousePosition.value.y);

    const speedFactor = 1 / props.scale;

    // Nouvelle position du conteneur
    const newBoxPosition = {
      x: boxPosition.value.x + (deltaX * speedFactor),
      y: boxPosition.value.y + (deltaY * speedFactor),
    };

    // Mise à jour de la position
    updatePosition(newBoxPosition);

    // Mise à jour de la dernière position de la souris
    lastMousePosition.value = { x: event.clientX, y: event.clientY };
  }
};

// Fonction pour arrêter le glisser-déposer
const stopDrag = () => {
  if (isDragging.value) {
    isDragging.value = false;

    // Nouvelle position avec effet de magnétisme à la grille
    const newBoxPosition = {
      x: Math.round(boxPosition.value.x / gridMagnetEffectSize) * gridMagnetEffectSize,
      y: Math.round(boxPosition.value.y / gridMagnetEffectSize) * gridMagnetEffectSize,
    };

    // Mise à jour de la position
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
