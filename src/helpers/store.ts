import { defineStore } from 'pinia';
import { ref } from 'vue';


export const mouseStore = defineStore({
  id: 'mouseStore',
  state: () => ({
    mouseX: ref(0),
    mouseY: ref(0),
  }),
});


export const canvasStore = defineStore('canvasStore', () => {
  const zoom = ref(1);

  return { zoom }
});

// export default {mouseStore, canvasStore};
