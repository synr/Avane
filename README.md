<p align="center">
  <img src="http://imgur.com/ANJiiSB.png"/>
</p>
<p align="center">
  <i>x.</i>
</p>

&nbsp;

# Avane
亞凡芽是基於 PHP 的一套模板引擎，其功能支援隨機 CSS 樣式名稱，

同時整合 JS 檔案。

&nbsp; 

# 特色

1. 支援 PJAX（換網頁不重整）

2. 減少撰寫 PHP 程式的次數。 

3. 支援多重模板主題。 

4. 支援整合 JS 和 CSS 檔案。

5. 支援隨機 CSS 樣式名稱編譯。 



&nbsp; 

# 索引

1. 範例

2. 初始化
  
  * 設置模板主題

3. 輸出

  * 只取得內容而不輸出
  
  * 傳入模板原始碼取得編譯後的內容

4. 模板標籤

&nbsp; 

# 範例

你可以透過下列方式讀取一個模板。

```php
$avane = new Avane();

$avane->header('相簿') // 設定標題為「相簿」
      ->load('album')  // 讀取名為 album 的模板檔案
      ->footer();      // 讀取頁腳
```

&nbsp; 

# 初始化

你需要先初始化亞凡芽，之後才能對它進行一些設置。

```php
$avane = new Avane();
```

&nbsp; 

## 設置模板主題

亞凡芽支援多個模板主題，在沒有設置模板主題的情況下，會指向 default 這個主題。

```php
->setTemplate('模板資料夾名稱')
```

&nbsp; 

# 輸出

要輸出亞凡芽成為一個網頁內容，請透過下列方式，可以順便在輸出時傳入變數給予該模板。

```php
$avane->header('網頁標題', ['變數1' => 'Caris', 
                           '變數2' => 'Dalan!'])
      ->load('模板名稱')
      ->footer();
```

&nbsp; 

## 只取得內容而不輸出

有時候你可能想要取得輸出內容**而不是將它展現在網頁上**，這個時候，請透過 `fetch()`，

請注意這個時候 `header()` 和 `footer()` 將會不管用。

```php
$content = $avane->fetch(模板名稱, 變數陣列);
```

&nbsp; 

## 傳入模板原始碼取得編譯後的內容

這個方法和 `fetch()` 略微相同，其差異在於 `rawFetch()` 編譯的來源**不是來自一個模板檔案**，

而是將傳入的文字編譯成模板，然後回傳其結果而不是輸出。

```php
$content = $avane->rawFetch(模板內容, 變數陣列);
```

&nbsp; 

# 模板標籤

模板標籤是亞凡芽裡最重要的一部份，模板標籤省下了你撰寫 PHP 程式的時間。

標籤分為兩類：

1. **直式標籤**：這個標籤通常是用來輸出變數內容用的，例如 { name }。

2. **輔助標籤**：這個標籤通常是一種函式，例如 {% if %} 或 {* 註釋 *}。

&nbsp; 

## 變數

在亞凡芽裡要輸出一個變數是十分簡單地，透過 `{ 變數名稱 }` 即可。

```html
<span>你好！我是 {name} 喔！</span>
```

&nbsp; 

倘若你要輸出一個 PHP 變數而不是亞凡芽變數，則在變數名稱前加上金錢符號。

```html
<span>為什麼我會知道？不是因為我叫 Trivago，而是因為我是 {$name}。</span>
```
