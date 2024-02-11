import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useStore = defineStore({
  id: 'store',
  state: () => ({
    mouseX: ref(0),
    mouseY: ref(0),
  }),
});

export default useStore;
