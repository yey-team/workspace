export let boxes = [
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


export function newBlock(blocType) {
    boxes.push(
      { id: (boxes.length + 1).toString(),
        position: {x: 0,y: 0},
        content: "Boîte " + (boxes.length + 1),
        type: blocType
  })
  return (boxes.length + 1).toString()
}

export function updateBoxes(newBoxes) {
  boxes = newBoxes;
}

export default {newBlock, updateBoxes} 