import { defineStore } from 'pinia'
import { ref } from 'vue';

export const Store = defineStore('store', () => {
    const mouseX = ref(0)
    const mouseY = ref(0)
    const coordCanvas = ref({x: 0,y: 0})


    const menus = ref([
      // {
      //   position: {x: 50,y: 50},
      //   scale: {x: 1,y: 1},
      //   config: 'Boîte 1',
      // }
    ])

    // function translateTo(newX, newY){
    //   coordCanvas.value = { newX, newY }
    // }
  
    return { mouseX, mouseY, coordCanvas, menus }
  })