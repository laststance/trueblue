import Constants from '../constants/home'
import { getYmdStr } from '../utils/util'

const Actions = {
    fetchSingleDate: (username, date) => {
        return dispatch => {
            dispatch({ type: Constants.AJAX_FETCH_START })
            fetch('/ajax/' + username + '/' + date, {
                credentials: 'include'
            }).then((response) => {
                return response.json()
            }).then((data) => {
                dispatch({
                    type: Constants.FETCH_SINGLE_DATE,
                    timelineJson: data,
                    currentDate: date
                })
            })
        }
    },
    import: () => {
        return dispatch => {
            fetch('/ajax/initial/import', {
                credentials: 'include'
            }).then((response) => {
                return response.json()
            }).then((data) => {
                dispatch({
                    type: Constants.DONE_IMPORT
                })
            })
        }
    },
    debugImport: () => {
        return dispatch => {
            dispatch({
                type: Constants.DONE_IMPORT
            })
        }
    },
    setCurrentDate: (ymdString) => {
        return dispatch => {
            dispatch({
                type: Constants.SET_CURRENT_DATE,
                currentDate: ymdString
            })
        }
    },
    setCurrentIndex: (i) => {
        return dispatch => {
            dispatch({
                type: Constants.SET_CURRENT_INDEX,
                currentIndex: i
            })
        }
    },
    // menu.jsx
    moveToSpecificDate: (dateObj) => {
        const ymdString = getYmdStr(dateObj[0]);
        const i = 10; // TODO

        return dispatch => {
            dispatch({
                type: Constants.MOVE_TO_SPECIFIC_DATE,
                currentDate: ymdString,
                currentIndex: i
            })
        }
    }
}

export default Actions
