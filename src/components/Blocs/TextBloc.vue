<template>
  <div>
    <!-- Aperçu du texte Markdown -->
    <div class="markdown-preview" v-html="markdownPreview"></div>
    
    
    <!-- Zone de texte pour l'entrée utilisateur -->
    <textarea v-model="inputText" @input="updatePreview" class="input-text"></textarea>

  </div>
</template>

<script setup>
import { ref } from 'vue';
import MarkdownIt from 'markdown-it';
const md = new MarkdownIt(); // Initialiser l'instance de markdown-it

// Utilisation de la réactivité de Vue pour lier la valeur du texte
const inputText = ref("");
const markdownPreview = ref("");

// Fonction pour mettre à jour l'aperçu Markdown
const updatePreview = () => {
  markdownPreview.value = md.render(inputText.value);
};
</script>

<style scoped>
.input-text {
  width: auto;

  height: auto;
  overflow: auto;

  background-color: var(--main-div-color);
  color: var(--main-text-color);
  border: 1px solid var(--border-color);
  
  padding: 1rem;
}

.markdown-preview {
  
  width: auto;

  height: auto;
  overflow: auto;

  background-color: var(--main-div-color);
  color: var(--main-text-color);
  border: 1px solid var(--border-color);
  
  padding: 1rem;
}
</style>
