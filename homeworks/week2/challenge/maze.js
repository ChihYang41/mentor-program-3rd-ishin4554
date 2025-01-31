function maze(arr) {
  const mazeArr = arr;
  let now = 1;
  let next = 1;
  let sum = 0;
  for (let i = 0; i < mazeArr.length - 1; i += 1) {
    next = mazeArr[i + 1].indexOf('.', now);
    if (next === -1) {
      next = mazeArr[i + 1].indexOf('.', 0);
    }
    sum += Math.abs(now - next) + 1;
    now = next;
  }
  return sum;
}
console.log(maze([
  '#.########',
  '#........#',
  '########.#',
  '#........#',
  '#.######.#',
  '#........#',
  '#.########',
  '#........#',
  '#.######.#',
  '########.#']));

console.log(maze([
  '#.########',
  '#........#',
  '########.#',
  '#........#',
  '#.########',
  '#........#',
  '########.#',
  '#........#',
  '#.######.#',
  '########.#']));

console.log(maze([
  '#.########',
  '#........#',
  '#........#',
  '#........#',
  '#........#',
  '#........#',
  '#........#',
  '#........#',
  '#........#',
  '########.#']));
