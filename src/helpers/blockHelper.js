export let boxes = [
  {
    position: {x: 50,y: 50},
    content: 'Boîte 1',
    type: "image"
  },{
    position: {x: 200,y: 100},
    content: 'Boîte 2',
    type: "text"
  }
];


export function newBloc(blocType) {
    boxes.push(
      {position: {x: 0,y: 0},
        content: "Boîte " + (boxes.length + 1),
        type: blocType
  })
}

export function updateBoxes(newBoxes) {
  boxes = newBoxes;
}

export default {newBloc, updateBoxes} 