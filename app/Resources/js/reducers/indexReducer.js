import Constants from '../constants/index'

export const initialState = {
    fetching: false,
    timelineDateList: [],
    timelineJson: {},
    appUsername: ''
}

export default function indexReducer(state = initialState, action) {
    switch (action.type) {
    case Constants.FETCH_DAILY_TWEET:
        return {...state, fetching: true}
    case Constants.DAILY_TWEET_RECEIVED:
        return {...state, timelineJson: action.timelineJson, fetching: false}

    default:
        return state
    }
}
