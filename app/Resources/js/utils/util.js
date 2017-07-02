// Date() → "2020-01-08"
export function getYmdStr(date)
{
    return date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2)
}

export function getKaomoji() {
    const kaomojiList = ['ｏ口(・∀・ )', '(*ノ・ω・）', 'o (◡‿◡✿)', 'ヽ(*・ω・)ﾉ']
    const rand = Math.round(Math.random() * (kaomojiList.length - 1))
    return kaomojiList[rand]
}

export function isSP() {
    if (window.parent.screen.width <= 544) {
        return true
    } else {
        return false
    }
}

export function getJsonKeys(json) {
    var r = []
    for (const k in json) {
        r.push(k)
    }
    
    return r
}
