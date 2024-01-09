<template>
  <div ref="myDiv" @dblclick="handleClickInside" class="content">
    <div class="editor" @click.stop v-if="isInside" @mousedown.stop>
      <div class="md-preview" v-html="renderedMarkdown"></div>
      <textarea class="text-input" v-model="textareaContent" @keyup="renderMarkdown()"></textarea>
    </div>
    <div class="md-preview" @click.stop v-else ref="markdownContent" v-html="renderedMarkdown" @dblclick="handleClickInside"></div>
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
  width: max-content;
  height: 100%;
  min-height: 100px;
  min-width: 200px;
  
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

  border: solid 1px var(--border-color);
  border-radius: var(--large-border-radius);


  color: var(--main-text-color);
  outline: none;

  padding: 1rem;
  background-color: var(--main-div-color);

  max-width: -webkit-fill-available;
}



.md-preview {
  border: solid 1px var(--border-color);
  border-radius: var(--large-border-radius);

  padding: 1rem;

  background-color: var(--main-background-color);
}

.md-preview pre {
  padding: 0.5rem;
  border-radius: calc(var(--large-border-radius) / 2);
  background-color: black;
}
</style>
