<!-- ArrowComponent.vue -->
<template>
    <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
      <path
        :d="`M ${sx} ${sy} C ${c1x} ${c1y}, ${c2x} ${c2y}, ${ex} ${ey}`"
        stroke="black"
        :stroke-width="arrowHeadSize / 2"
        fill="none"
      />
      <polygon
        :points="`0,${-arrowHeadSize} ${arrowHeadSize * 2},0, 0,${arrowHeadSize}`"
        :transform="`translate(${ex}, ${ey}) rotate(${ae})`"
        fill="black"
      />
    </svg>
  </template>
  
  <script setup>
  import { ref, watchEffect } from 'vue';
  import { getArrow } from 'curved-arrows';
  
  const props = defineProps(['startPoint', 'endPoint']);
  
  const arrowHeadSize = 9;
  
  // Variables réactives pour stocker les valeurs calculées
  const sx = ref(0);
  const sy = ref(0);
  const c1x = ref(0);
  const c1y = ref(0);
  const c2x = ref(0);
  const c2y = ref(0);
  const ex = ref(0);
  const ey = ref(0);
  const ae = ref(0);
  
  // Fonction pour mettre à jour les valeurs
  function updateValues() {
    const [newSx, newSy, newC1x, newC1y, newC2x, newC2y, newEx, newEy, newAe] = getArrow(
      props.startPoint.x,
      props.startPoint.y,
      props.endPoint.x,
      props.endPoint.y,
      {
        padEnd: arrowHeadSize,
      }
    );
  
    // Mettez à jour les valeurs réactives
    sx.value = newSx;
    sy.value = newSy;
    c1x.value = newC1x;
    c1y.value = newC1y;
    c2x.value = newC2x;
    c2y.value = newC2y;
    ex.value = newEx;
    ey.value = newEy;
    ae.value = newAe;
  }
  
  // Effect réactif pour surveiller les changements de startPoint et endPoint
  watchEffect(() => {
    updateValues();
  });
  
  // Appelez la fonction initiale pour initialiser les valeurs
  updateValues();
  </script>
  