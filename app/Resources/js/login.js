import '../sass/common.scss';
import '../sass/login.scss';

// スマホのタッチスクリーンでボタンのhoverイベントを有効にする
$('.btn-twitter-login').on('touchstart', () => {
    thisAnchor = $(this);
    touchPos = thisAnchor.offset().top;
    //タッチした瞬間のa要素の、上からの位置を取得。
    moveCheck = () => {
        nowPos = thisAnchor.offset().top;
        if (touchPos == nowPos) {
            thisAnchor.addClass("hover");
            //タッチした瞬間と0.1秒後のa要素の位置が変わっていなければ hover クラスを追加。
            //リストなどでa要素が並んでいるときに、スクロールのためにタッチした部分にまでhover効果がついてしまうのを防止している。
        }
    }
    setTimeout(moveCheck, 100);
    //0.1秒後にmoveCheck()を実行。
});
