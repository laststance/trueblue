import Constants from '../constants/home'
import { getYmdStr } from '../utils/util'

// TODO Require Export?
export const initialState = {
    fetching: false,
    timelineJson: {},
    username: '',
    currentDate: getYmdStr(new Date()), // @TODO
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
    case Constants.SET_CURRENT_DATE_AND_INDEX:
        return {...state, currentDate: action.currentDate, currentIndex: action.currentIndex}

    default:
        return state
    }
}
