import Constants from '../constants/home'

export const initialState = {}

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
        return {...state, currentDate: action.currentDate}
    case Constants.SET_CURRENT_INDEX:
        return {...state, currentIndex: action.currentIndex}
    case Constants.MOVE_TO_SPECIFIC_DATE:
        return {...state,currentDate: action.currentDate, currentIndex: action.currentIndex}

    default:
        return state
    }
}
