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
    it('undefined Action', () => {
        expect(homeReducer(initialState, {})).toEqual(
            initialState
        )
    })
    it('AJAX_FETCH_START', () => {
        initialState.fetching = true
        const expectState = initialState

        expect(homeReducer(initialState, {
            type: Constants.AJAX_FETCH_START
        })).toEqual(
            expectState
        )
    })
    it('FETCH_SINGLE_DATE', () => {
        initialState.fetching = false
        const expectState = initialState

        expect(homeReducer(initialState, {
            type: Constants.FETCH_SINGLE_DATE,
            timelineJson: {},
            currentDate: initialState.currentDate
        })).toEqual(
            expectState
        )
    })
    it('DONE_IMPORT', () => {
        initialState.isShowImportModal = false
        const expectState = initialState

        expect(homeReducer(initialState, {
            type: Constants.DONE_IMPORT,
            currentDate: initialState.currentDate
        })).toEqual(
            expectState
        )
    })
    it('SET_CURRENT_DATE', () => {
        initialState.currentDate = '2020-12-20'
        const expectState = initialState

        expect(homeReducer(initialState, {
            type: Constants.SET_CURRENT_DATE,
            currentDate: '2020-12-20'
        })).toEqual(
            expectState
        )
    })
})
