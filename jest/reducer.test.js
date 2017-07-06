import homeReducer from '../app/Resources/js/reducers/homeReducer'
import { getYmdStr } from '../app/Resources/js/utils/util'

describe('homeReducer', () => {
    it('not give action type, return initial state', () => {
        expect(homeReducer(undefined, {})).toEqual(
            {
                fetching: false,
                timelineJson: {},
                username: '',
                currentDate: getYmdStr(new Date())
            }
        )
    })
})
