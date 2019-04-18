// 給定一字串，把第一個字轉成大寫之後「回傳」，若第一個字不是英文字母則忽略。
function capitalize(str) {
  const firstASCII = str.charCodeAt(0);
  let newStr = str;
  if (firstASCII >= 97 && firstASCII <= 122) {
    newStr = str.replace(str[0], str[0].toUpperCase());
  }
  return newStr;
}

console.log(capitalize(',hello'));
