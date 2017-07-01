import Constants from '../constants/home'
import {getYmdStr} from '../utils/util'
import {getObjectKeyIndex} from '../utils/util'

export const initialState = {
    fetching: false,
    timelineJson: {},
    username: '',
    currentDate: getYmdStr(new Date()),
    currentIndex: 1
}

export default function homeReducer(state = initialState, action) {
    switch (action.type) {
    case Constants.AJAX_FETCH_START:
        return {...state, fetching: true}
    case Constants.FETCH_SINGLE_DATE:
        // push
        return {...state, timelineJson: action.timelineJson, fetching: false, currentDate: action.currentDate}
    case Constants.DONE_IMPORT:
        return {...state, isShowImportModal: false}
    case Constants.SET_CURRENT_DATE:
        return {...state, currentDate: action.currentDate, currentIndex: getObjectKeyIndex(state.timelineJson, action.currentDate)}
    case Constants.SET_CURRENT_INDEX:
        return {...state, currentIndex: action.index}

    default:
        return state
    }
}
