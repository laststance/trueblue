// Date() → "2020-01-08"
export function getYmdStr(date)
{
    return date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2)
}
