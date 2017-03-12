import Constants from '../constants/home'

const Actions = {
    fetchDailyTweet: (username, date) => {
        return dispatch => {
            dispatch({ type: Constants.FETCH_DAILY_TWEET })
            fetch('/ajax/' + username + '/' + date, {
                credentials: 'include'
            }).then((response) => {
                return response.json()
            }).then((data) => {
                dispatch({
                    type: Constants.DAILY_TWEET_RECEIVED,
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
    }
}

export default Actions
