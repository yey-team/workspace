import {v4 as uuidv4} from 'uuid';


/* -------------------------------------------------------------------------- */
/*                                 Interfaces                                 */
/* -------------------------------------------------------------------------- */

interface Arrow {
  id: string;
  firstBlock: string;
  secondBlock: string;
}


/* -------------------------------------------------------------------------- */
/*                                  Variables                                 */
/* -------------------------------------------------------------------------- */


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


export function addArrow(from:string, to:string){
  const newId = uuidv4();
  arrows.push({
    id: newId,
    firstBlock: from,
    secondBlock: to,
  });
  return newId;
}


/* -------------------------------------------------------------------------- */
/*                                Export datas                                */
/* -------------------------------------------------------------------------- */

const exportedData = { addArrow, arrows };

export default exportedData;
