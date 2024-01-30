import { defineStore } from 'pinia'

export const Store = defineStore('store', () => {
    const mouseX = ref(0)
    const mouseY = ref(0)
  
    return { mouseX, mouseY, doubleCount, increment }
  })