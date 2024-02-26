import { defineStore } from 'pinia';
import { ref } from 'vue';



export const useMouseStore = defineStore({
  id: 'mouseStore',
  state: () => ({
    mouseX: ref(0),
    mouseY: ref(0),
  }),
  actions: {
    // Ajoute une mutation pour mettre à jour la valeur de zoom
    setPosition(newMouseX: number, newMouseY: number) {
      this.mouseX = newMouseX;
      this.mouseY = newMouseY;
    },
  },
});

export const useCanvasStore = defineStore({
  id: 'canvasStore',
  state: () => ({
    zoom: ref(1),
  }),
  actions: {
    // Ajoute une mutation pour mettre à jour la valeur de zoom
    setZoom(newZoom: number) {
      this.zoom = newZoom;
    },
  },
});


export const useMenusStore = defineStore({
  id: 'menusStore',
  state: () => ({
    menus: ref([] as any[]),
  }),
  actions: {
    // Ajoute une mutation pour mettre à jour la valeur de zoom
    addMenu(menu: any) {
      this.menus.push(menu);
    },
  },
});

