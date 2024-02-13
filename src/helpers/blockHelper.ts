/* -------------------------------------------------------------------------- */
/*                                 Interfaces                                 */
/* -------------------------------------------------------------------------- */


interface Block {
  id: string;
  position: { x: number; y: number };
  content: string;
  type: string;
  links: string[];
}

interface Arrow {
  id: string;
  firstBlock: string;
  secondBlock: string;
}


/* -------------------------------------------------------------------------- */
/*                                  Variables                                 */
/* -------------------------------------------------------------------------- */


let blocks: Block[] = [
  {
    id: "0",
    position: { x: 50, y: 50 },
    content: 'Boîte 1',
    type: "image",
    links: [],
  },
  {
    id: "1",
    position: { x: 200, y: 100 },
    content: 'Boîte 2',
    type: "text",
    links: [],
  },
];

let arrows: Arrow[] = [
  {
    id: "0",
    firstBlock: "0",
    secondBlock: "1",
  },
];


/* -------------------------------------------------------------------------- */
/*                                  Functions                                 */
/* -------------------------------------------------------------------------- */

export function newBlock(blockType: string, xPos = 0, yPos = 0): string {
  const newId = (blocks.length + 1).toString();
  blocks.push({
    id: newId,
    position: { x: xPos, y: yPos },
    content: `Boîte ${blocks.length + 1}`,
    type: blockType,
    links: [],
  });
  return newId;
}

export function updateBlocks(newBlocks: Block[]): void {
  blocks = newBlocks;
}

export function updateSingleBlock(id: string, newObject: Block) {
  const index = blocks.findIndex(objet => objet.id === id);

  if (index !== -1) {
    // Remplace l'objet à l'index trouvé par le nouvel objet
    blocks.splice(index, 1, newObject);
  }
};

export function getBlockById(blocks: Block[], id: string): Block | undefined {
  return blocks.find(block => block.id === id);
}


/* -------------------------------------------------------------------------- */
/*                                Export datas                                */
/* -------------------------------------------------------------------------- */

const exportedData = { newBlock, updateBlocks, getBlockById, updateSingleBlock, blocks, arrows };

export default exportedData;
