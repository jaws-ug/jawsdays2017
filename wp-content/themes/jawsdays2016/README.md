# JAWS DAYS 2016 テーマ

@link https://github.com/megumiteam/iemoto

このテーマはJAWS DAYS 2016用テーマです。

## Sass(Compass)

style.css および editor-style.css の作成に Sass および Compass を利用しています。利用する場合は事前にインストールしておいてください。

* Sass: http://sass-lang.com/
* Compass: http://compass-style.org/

## gulp

js や sass (Compass) のコンパイルなどに [gulp.js](http://gulpjs.com/) を利用しています。利用する場合は事前にインストールしておいてください。

```
$ npm install --global gulp
```

* gulp.js: http://gulpjs.com/

## 使い方

git clone でコピー後、 `npm install` コマンドを実行して `gulp` の実行に必要なファイルをダウンロードしてください。

```
$ npm install
```

## gulp での Sass のコンパイルや JavaScript のチェック

`.js` や `.scss` などのファイルを修正したら、以下のコマンドを実行して下さい。 

```
$ gulp
```

JavaScript のみの場合は `gulp js` 、Sass(Compass) のみの場合は `gulp compass` も利用できます。

## watch について

`gulp watch` を使えば、ファイルの更新を grunt(gulp) が監視し、自動的に Sass(Compass) のコンパイル、minify の作業を行います。


```
$ gulp watch
```

watch を終了するには、キーボードで `[control]+[c]` を押して下さい。

## Debug モードと Sourcemap

WordPress をデバッグモードにしていると、テーマは Sourcemap が付与された `css/style.css` を読み込みます。

Sourcemap の作成には Sass 3.3.0 以上が必要です。