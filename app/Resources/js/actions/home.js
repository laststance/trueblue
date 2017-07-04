import Constants from '../constants/home'

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
    setCurrentDateAndIndex: (ymdString, currentIndexNumber) => {
        return dispatch => {
            dispatch({
                type: Constants.SET_CURRENT_DATE_AND_INDEX,
                currentDate: ymdString,
                currentIndex: currentIndexNumber
            })
        }
    }
}

export default Actions
