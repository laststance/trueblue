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
