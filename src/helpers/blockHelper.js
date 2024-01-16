let boxes = [
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


function module() {
  this.newBloc = (allBlock, blocType) => {
    allBlock.push(
      {position: {x: 0,y: 0},
        content: "Boîte " + (allBlock.length + 1),
        type: blocType
      })
  }
}

export default module 