<template>
  <div ref="myDiv" @dblclick="handleClickInside" class="content" @mousedown.stop>
    <div class="editor" @click.stop v-if="isInside">
      <div class="resizable">
        <img class="image" src="https://imgupscaler.com/images/samples/animal-after.webp">
      </div>
      <input type="url" class="text-input" v-model="inputContent" @keypress="renderImage"/>
    </div>
    <div class="resizable" @click.stop v-else ref="inputContent" @dblclick="handleClickInside">
      <img class="image" src="https://imgupscaler.com/images/samples/animal-after.webp">
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const inputContent = ref('');  // Utilise une variable distincte pour le contenu du textarea
const myDiv = ref(null);
const isInside = ref(false);

const handleClickInside = () => {
  isInside.value = true;
  console.log("inside");
};

const handleClickOutside = (event) => {
  if (myDiv.value && !myDiv.value.contains(event.target)) {
    console.log("outside");
    isInside.value = false;
    renderImage();
  }
};

onMounted(() => {
  document.addEventListener("click", handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener("click", handleClickOutside);
});

const renderImage = (event) => {
  if (event){
    if (event.keypress == "enter"){
      console.log('yey')
    }
  }else{
    console.log('yey')
  }
};
</script>

<style scoped>
.content {
  width: auto;
  height: 100%;
  min-height: 100px;
  min-width: 200px;
  
}

.editor{
  position: relative;
  width: fit-content;
  height: 100%;
}

.text-input{
  position: relative;
  width: 100%;
  height: 100%;

  border: solid 1px var(--border-color);
  border-radius: var(--large-border-radius);


  color: var(--main-text-color);
  outline: none;

  padding: 1rem;
  background-color: var(--main-div-color);

  max-width: -webkit-fill-available;
}



.resizable {
  border: solid 1px var(--border-color);
  border-radius: var(--large-border-radius);

  background-color: var(--main-background-color);
}


.resizable {
    display: inline-block;
    background: red;
    resize: both;
    overflow: hidden;
    line-height: 0;

    min-width: 200px;
    max-width: 1000px;

    min-height: 100px;

    width: max-content !important;

    border-radius: calc(var(--large-border-radius) / 2);
  }


  .image {
    min-width: 200px;
    max-width: 1000px;

    width: 100%;
    height: 100%;

    object-fit: cover;
  }
</style>
