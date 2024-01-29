export let blocks = [
  {
    id:"0",
    position: {x: 50,y: 50},
    content: 'Boîte 1',
    type: "image"
  },{
    id:"1",
    position: {x: 200,y: 100},
    content: 'Boîte 2',
    type: "text"
  }
];


export function newBlock(blockType) {
    blocks.push(
      { id: (blocks.length + 1).toString(),
        position: {x: 0,y: 0},
        content: "Boîte " + (blocks.length + 1),
        type: blockType
  })
  return (blocks.length + 1).toString()
}

export function updateBlocks(newBlocks) {
  blocks = newBlocks;
}

export default {newBlock, updateBlocks} 