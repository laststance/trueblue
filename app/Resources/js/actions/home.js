import Constants from '../constants/home'

const Actions = {
    fetchDailyTweet: (date) => {
        return dispatch => {
            dispatch({ type: Constants.FETCH_DAILY_TWEET })
            fetch('/ajax/daily/' + date, {
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
