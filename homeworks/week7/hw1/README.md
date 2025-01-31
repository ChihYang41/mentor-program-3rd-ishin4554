# 作業邏輯

## 核心邏輯
要設計一個可以從按下按鈕開始，隨機的一段秒數長度，然後再點擊時會判斷比隨機一段的秒數長度提早或超時多少，如果提早則失敗、超時則紀錄下相差多少時間的遊戲。

## 其他部分
- 隨機的一段秒數長度會變色
- 可以紀錄歷年相差多少時間並進行排序
- 變色也可以想怎麼變就怎麼變

# 第一版回顧
*注意：第一版心得是在四周後以回憶的方式撰寫，盡可能還原但可能會與現實有一點點差別。*

根據核心首先規劃核心功能的撰寫：
1. 計時器：計算從按下按鈕到再點擊畫面的時差
2. 遊戲時間產生器：產生隨機一段時間長度

基於上述的邏輯，按照過去經驗，滿多程式語言都會內建時間物件，所以先了解 javascript 裡面是不是也有這個設定，跟有哪些 methods，果不其然， methods 裡面有 `getTime()`, `getSeconds()` 等等的 methods。

原本的劇本預想是： javascript 內建的時間物件可以進行比較，所以核心的部分就會變成：
1. 計時器按下去得到一個時間物件
2. 計時器再按一次得到第二個時間物件
3. 第二個與第一個時間物件相減，由於時間物件的特性，會幫我變成幾秒、幾微秒
4. 與遊戲時間產生器的時間物件進行比較

不過沒有，所以計時器就會變得比原本預想的複雜一些，經過拆解：
1. 計時器: 這邊用 `Date` 內建的 method `now()` 來取得從一個基本時間到現在的微秒數
2. 換算工具: 將微秒轉換成幾秒幾微秒 
3. 遊戲時間產生器：產生隨機一段時間長度

由於點擊畫面的按鈕時，會遇到課程中所述的 bubbling 問題，所以也學以致用的用 `stopPropagation()` 來避免這件事情發生。

核心邏輯完成後，開始處理變色系統，變色系統這邊是根據原本遊戲時間產生器的部分來進行變色，所以可以理解成，按下按鈕後、過時間產生器的時間就變色，算是與核心平行的一個功能，這邊要學習的是延後執行這件事，也就是 javascript 的 `setInterval()`, `setTimeOut()` 與 `requestAnimationFrame()`

原本，最理想的狀態是使用 `setTimeOut()` 比較直覺，因為是一次延後，但當時因為找不到 `setTimeOut()` 失敗無法取消的原因，最後選擇用 `setInterval()` 來實踐。

取消 `setInterval()` 需要宣告一個變數來儲存他的 id（根據 [此篇回答](https://www.sitepoint.com/community/t/id-returned-by-setinterval/21831) 基本上這個 id 就是記憶體位置了），你才有辦法在之後被 `clearInterval()`。

以上變色系統大致完成，剩下是比較細節的 UI 邏輯，以及額外進階挑戰題的功能，歷史紀錄、變色與按鍵啟動：

- 歷史紀錄：基本上是把所有時間 `push` 到一個 array 後進行排序，並顯示時只顯示前三名，這邊就多用 function loop 減少不必要的 counter 宣告跟加強易讀性，這邊用 array method `sort()` 來解決這個問題，但因為只顯示前三名，所以這邊顯示的 loop 就是用傳統 for loop 來實踐。
- 變色：變色上如果要隨意的變色，可能會有很醜的問題，所以這邊了解了一下 style 的顏色系統，除了我們常見的 `Hex`, `RGB`, `RGBA` 還有 `HSL` 跟 `HSLA`，其中 `HSL` 系列就是根據色相、飽和度與明度來調整顏色，也就是我們可以在同一色系內進行變化。

# 第二版重構
*注意：本版以 clean code 為目標進行重構*

對於 function 怎麼 extract 其實還很模糊，第一版的時候覺得功能其實已經能切則切，看到後來幾週老師給予的建議，像是 selector 這些可以打包，當時的一個靈感方向就是：**jQuery 幫我們做的，就是應該被打包的 function** [^1]

所以這一版改版就以把印象中 jQuery 幫我們做的事情，給打包起來並優化命名。

首先回顧一下 code，最常出現的幾個：
1. `document.querySelector()` : `dq()` 
2. `style.property = ...` 用 `setStyle()` 打包 : 嘗試了讓參數也可以是物件的方式來操作 css，用了 `object.keys()` 跟 `object.valules()` 來進行操作。

Event Listener 沒有處理是基於 callback function 常常寫在裡面以 anonymous function 來處裡，要另外傳入 function 不是很方便。

另外一方面，也算是重新從自己原本的架構來重構程式碼，第一版的開發是跟著操作順序來開發，不符原先想像的架構來寫功能，所以第二版應該要跟著原本的規劃[^2]：
|Category|Functions|
|:---|:---|
|計數器系列|getTimeNow(), subTime(), transTime()|
|時間產生器|createRndTime()|
|遊戲邏輯|startGame(), stopGame()|
|排名|cleanGradeLs(),showGradeLs(), showGrade(), showResult()|
|UI 調整|changeBg(), changeBtn()|


# 第三版 OOP 重構
注意：本版以物件導向為目標進行重構

從第二版中，其實可以相對明顯地看見哪些東西會屬於同一物件，像是：時間系列的 function 應該可以打包成一個物件，於是嘗試將時間相關的 method 跟 variables 打包在一起，物件導向其實在設計上挺難的，

實踐的過程中有比較了解 OOP 的一些真相：
1. constructor 真的只有執行一次，不要懷疑，所以連動的 variable 記得 set 裡面要同步修改。
2. 寫的過程中有遇到 class 裡面的 variable 不能與 `set`, `get` 重名的問題。

# Code Review 

- 這個遊戲邏輯滿好的，[把 start time 塞在 setTimeOut 之後](https://github.com/Lidemy/mentor-program-3rd-sevensplus/blob/master/homeworks/week7/hw1/script.js)，然後用 start time 的狀態來做加減，可以少掉兩層運算（結束 - 最開始 - random 時間）
```javascript
//by sevensplus
games = setTimeout(function() {
    document.querySelector('body').style.background = color();
    start = Date.now();
  }, time);
//...
grade.push((end - start) / 1000);
```

- [16 位元隨機色產生器](https://github.com/Lidemy/mentor-program-3rd-nofear195/blob/master/homeworks/week7/hw1/index.html) 滿酷的，用 `.toString(16)` 轉 16 進位。
```javascript
//by cocoisbad
function randomNum() { 
  let ans = '#';
  for (let i = 0; i < 6; i += 1) {
    let num = Math.floor((Math.random() * 16));
    ans += num.toString(16);
  }
  return ans
}
```

`Array.sort` 之外的[寫法參考](https://github.com/Lidemy/mentor-program-3rd-julypenguin/blob/master/homeworks/week7/hw1/script.js)：
```javascript
//by julyfish
for (let i = 0; i < bestScores.length; i += 1) {
    if (bestScores[i] === 0) {
      bestScores.splice(i, 1, endScore);
      endScore = 0;
    }
    if (endScore < bestScores[i] && endScore !== 0) {
      bestScores.splice(i, 0, endScore);
      bestScores.pop();
      endScore = 0;
    }
    if (bestScores[i] !== 0) {
      const record = document.querySelector(`.record${i}`);
      record.innerText = `${i + 1}. ${bestScores[i]} 秒`;
      record.classList.add('visible');
    }
  }
```

另外一種[色彩製造機](https://github.com/Lidemy/mentor-program-3rd-julypenguin/blob/master/homeworks/week7/hw1/script.js)：
```javascript
//by julyfish
 const colorCode = 'abcdef1234567890';
  let color = '#';
  for (let i = 0; i < 6; i += 1) {
    const number = Math.floor(Math.random() * color.length);
    color += colorCode[number];
  }
```

[^1]: *之所以沒有使用 jQuery 是希望重構過程與課程規劃配合*
[^2]: *關於重構過程的拆分是因為練習的考量，以第一次重構來說已經接觸了 OOP 的概念*
