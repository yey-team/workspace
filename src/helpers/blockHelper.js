export let blocks = [
  {
    id:"0",
    position: {x: 50,y: 50},
    content: 'Boîte 1',
    type: "image",
    links: []
  },{
    id:"1",
    position: {x: 200,y: 100},
    content: 'Boîte 2',
    type: "text",
    links: []
  }
];

export let arrows = [
  {
    id:"0",
    firstBlock: "0",
    secondBlock: "1"
  }
]

export function newBlock(blockType, xPos = 0, yPos = 0) {
    blocks.push(
      { id: (blocks.length + 1).toString(),
        position: {x: xPos,y: yPos},
        content: "Boîte " + (blocks.length + 1),
        type: blockType
  })
  return (blocks.length + 1).toString()
}

export function updateBlocks(newBlocks) {
  blocks = newBlocks;
}


export default {newBlock, updateBlocks} 