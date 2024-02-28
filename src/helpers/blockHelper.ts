import { defineStore } from 'pinia';
import { v4 as uuidv4 } from 'uuid';

interface Block {
  id: string;
  position: { x: number; y: number };
  content: string;
  type: string;
  links: string[];
}

export const useBlockStore = defineStore({
  id: 'blockStore',
  state: () => ({
    blocks: [] as Block[],
  }),
  actions: {
    newBlock(blockType: string, xPos = 0, yPos = 0): string {
      const newId = uuidv4();
      this.blocks.push({
        id: newId,
        position: { x: xPos, y: yPos },
        content: `Boîte ${this.blocks.length + 1}`,
        type: blockType,
        links: [],
      });
      return newId;
    },
    updateBlocks(newBlocks: Block[]): void {
      this.blocks = newBlocks;
    },
    updateSingleBlock(id: string, newObject: Block): void {
      const index = this.blocks.findIndex((block) => block.id === id);

      if (index !== -1) {
        // Remplace l'objet à l'index trouvé par le nouvel objet
        this.blocks.splice(index, 1, newObject);
      }
    },
    getBlockById(id: string): Block | undefined {
      return this.blocks.find((block) => block.id === id);
    },
  },
});

// // Exportez le hook pour l'utiliser dans vos composants
// export function useStore() {
//   return useBlockStore();
// }
