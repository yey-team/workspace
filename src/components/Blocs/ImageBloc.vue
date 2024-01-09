<template>


  <div ref="myDiv" @dblclick="handleClickInside" class="content" @mousedown.stop>

    <div class="editor" @click.stop v-if="isInside">

      <div class="resizable">
        <img class="image" :src="imageUrl">
      </div>

      <input type="url" ref="textInput" class="text-input" v-model="imageUrl"/>
      
    </div>

    <div class="resizable" @click.stop v-else ref="inputContent" @dblclick="handleClickInside">
      <img class="image" :src="imageUrl">
    </div>

  </div>

</template>



<script setup>


import { ref, onMounted, onUnmounted } from 'vue';

const inputContent = ref('');
const myDiv = ref(null);
const isInside = ref(false);

const textInput = ref(null);

let imageUrl = "https://helpx.adobe.com/content/dam/help/en/photoshop/using/convert-color-image-black-white/jcr_content/main-pars/before_and_after/image-before/Landscape-Color.jpg";

/* -------------------------------------------------------------------------- */
/*                                Handle clicks                               */
/* -------------------------------------------------------------------------- */

const handleClickInside = () => {
  isInside.value = true;
};

const handleClickOutside = (event) => {
  if (myDiv.value && !myDiv.value.contains(event.target)) {
    isInside.value = false;
  }
};

onMounted(() => {
  document.addEventListener("click", handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener("click", handleClickOutside);
});



/* -------------------------------------------------------------------------- */
/*                                   Render                                   */
/* -------------------------------------------------------------------------- */

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
