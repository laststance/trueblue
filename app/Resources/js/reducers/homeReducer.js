import Constants from '../constants/home'
import { getYmdStr } from '../utils/util'

export const initialState = {
    fetching:         false,
    timelineDateList: [],
    timelineJson:     {},
    username:         '',
    currentDate:      getYmdStr(new Date())
}

export default function homeReducer(state = initialState, action) {
    switch (action.type) {
    case Constants.FETCH_DAILY_TWEET:
        return {...state, fetching: true}
    case Constants.DAILY_TWEET_RECEIVED:
        return {...state, timelineJson: action.timelineJson, fetching: false, currentDate: action.currentDate}
    case Constants.DONE_IMPORT:
        return {...state, isShowImportModal: false}
        
    default:
        return state
    }
}
