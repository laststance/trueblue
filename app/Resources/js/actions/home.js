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
                    timelineJson: data
                })
            })
        }
    }
}

export default Actions
