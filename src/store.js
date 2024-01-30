import { defineStore } from 'pinia'

export const Store = defineStore('store', () => {
    const mouseX = ref(0)
    const mouseY = ref(0)
    const coordCanvas = ref({x: 0,y: 0})

    // function translateTo(newX, newY){
    //   coordCanvas.value = { newX, newY }
    // }
  
    return { mouseX, mouseY, coordCanvas }
  })