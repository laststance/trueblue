import $ from 'jquery'

import '../sass/common/common.scss'
import '../sass/page/index.scss'

// スマホのタッチスクリーンでボタンのhoverイベントを有効にする
$('.menu-btn').on('touchstart', function() {
    const thisAnchor = $(this)
    const touchPos = thisAnchor.offset().top
    //タッチした瞬間のa要素の、上からの位置を取得。
    const moveCheck = () => {
        const nowPos = thisAnchor.offset().top
        if (touchPos == nowPos) {
            thisAnchor.addClass('hover')
            //タッチした瞬間と0.1秒後のa要素の位置が変わっていなければ hover クラスを追加。
            //リストなどでa要素が並んでいるときに、スクロールのためにタッチした部分にまでhover効果がついてしまうのを防止している。
        }
    }
    setTimeout(moveCheck, 100)
    //0.1秒後にmoveCheck()を実行。
})

// ヘッダーの高さ分だけメインコンテンツにmargin-topを指定する(レスポンシブなので画面サイズによってヘッダーの高さが変わる)
$(() => {
    calcHeight()
})
$(window).on('resize', () => {
    calcHeight()
})

const calcHeight = function () {
    let navHeight = $('nav.navbar').outerHeight()
    $('.service-infomation').css('margin-top', navHeight + 'px')
}
