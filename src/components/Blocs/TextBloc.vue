<template>
  <div ref="myDiv" @click="handleClickInside" class="content">
    <div class="editor" @click.stop v-if="isInside">
      <div v-html="renderedMarkdown"></div>
      <textarea class="text-input" v-model="textareaContent" @keypress="renderMarkdown()"></textarea>
    </div>
    <div @click.stop v-else ref="markdownContent" v-html="renderedMarkdown" @click="handleClickInside"></div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import MarkdownIt from 'markdown-it';

const renderedMarkdown = ref('');
const textareaContent = ref('');  // Utilise une variable distincte pour le contenu du textarea
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
    renderMarkdown();  // Ne prend pas de paramètre, utilise textareaContent.value
  }
};

onMounted(() => {
  document.addEventListener("click", handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener("click", handleClickOutside);
});

const md = new MarkdownIt();
const renderMarkdown = () => {
  const renderedHtml = md.render(textareaContent.value);
  renderedMarkdown.value = renderedHtml;
  return renderedHtml;
};
</script>

<style scoped>
.content {
  width: 100%;
  height: 100%;
  min-height: 100px;
  border: solid 1px white;
}


.editor{
  position: relative;
  width: 100%;
  height: 100%;
}

.text-input{
  position: relative;
  width: 100%;
  height: 100%;

  min-height: 100px;
  min-width: 200px;
}
</style>
