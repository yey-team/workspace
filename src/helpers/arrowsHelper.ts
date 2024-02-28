import { defineStore } from 'pinia';
import { v4 as uuidv4 } from 'uuid';

/* -------------------------------------------------------------------------- */
/*                                 Interfaces                                 */
/* -------------------------------------------------------------------------- */

interface Arrow {
  id: string;
  firstBlock: string;
  secondBlock: string;
}

/* -------------------------------------------------------------------------- */
/*                                  Store                                     */
/* -------------------------------------------------------------------------- */

export const useArrowStore = defineStore({
  id: 'arrowStore',
  state: () => ({
    arrows: [] as Arrow[],
  }),
  actions: {
    newArrow(from: string, to: string): string {
      const newId = uuidv4();
      this.arrows.push({
        id: newId,
        firstBlock: from,
        secondBlock: to,
      });
      return newId;
    },
    getArrowById(id: string): Arrow | undefined {
      return this.arrows.find((arrow) => arrow.id === id);
    },
    getTargetsByOrigin(originID: string): string[] {
      const matchingArrows = this.arrows
        .filter((arrow) => arrow.firstBlock === originID)
        .map((arrow) => arrow.secondBlock);

      return matchingArrows;
    },
    getOriginsByTarget(targetID: string): string[] {
      const matchingArrows = this.arrows
        .filter((arrow) => arrow.secondBlock === targetID)
        .map((arrow) => arrow.firstBlock);

      return matchingArrows;
    },
  },
});

// // Exportez le hook pour l'utiliser dans vos composants
// export function useArrowStoreHooks() {
//   return useArrowStore();
// }
