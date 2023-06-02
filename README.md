# Tech Base CampのPHP個人課題
## To Do管理ウェブアプリ

### 主な機能
- ログイン/サインアップ機能
- To Doタスクの追加
- タスクの「完了」「削除」
  - 「完了」の場合はタスク表には表示されないがデータベースには残る
  - 「削除」の場合はデータベースからも削除される
- タスクの「編集」


### 苦労したところ/感想など
- 変数の呼び出し方/配置する場所がそれぞれの関数、言語によって変わることへの対応が難しかった
- 画面毎にhtmlファイルとphpファイルを別々に作成した
  - 個人的にはプログラムとウェブページを分けることができたので見やすかったが、「.htmlファイルにログインしなくても入れてしまう」というようなバグが回収できなかった。
- 時間的にCSSに挑戦することができなかった
