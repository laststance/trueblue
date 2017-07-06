import homeReducer from '../app/Resources/js/reducers/homeReducer'
import Constants from '../app/Resources/js/constants/home'
import { getYmdStr } from '../app/Resources/js/utils/util'

const initialState = {
    fetching: false,
    timelineJson: {},
    username: '',
    currentDate: getYmdStr(new Date())
}

describe('homeReducer', () => {
    it('not give action type, return initial state', () => {
        expect(homeReducer(undefined, {})).toEqual(
            initialState
        )
    })
    it('give AJAX_FETCH_START', () => {
        initialState.fetching = true

        expect(homeReducer(undefined, {
            type: Constants.AJAX_FETCH_START
        })).toEqual(
            initialState
        )
    })
})
