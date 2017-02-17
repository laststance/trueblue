import React from 'react'
import { connect } from 'react-redux'
import Timeline from '../components/timeline'
import Header from '../components/header'
import Actions from '../actions/home'

import '../../sass/common/common.scss'
import '../../sass/page/home.scss'

class App extends React.Component {
    render() {
        return (
            <div>
                <Header
                    timelineDateList={this.props.timelineDateList}
                    appUsername={this.props.appUsername}
                    fetchDailyTweet={this.props.fetchDailyTweet}
                    isLogin={this.props.isLogin}
                />
                <Timeline timelineJson={this.props.timelineJson}/>
            </div>
        )
    }
}

const mapStateToProps = (state) => (
    {
        timelineDateList: state.homeState.timelineDateList,
        timelineJson: state.homeState.timelineJson,
        appUsername: state.homeState.appUsername,
        isLogin: state.homeState.isLogin
    }
)

function mapDispatchToProps(dispatch) {
    return {
        fetchDailyTweet: function (date) {
            dispatch(Actions.fetchDailyTweet(date))
        }
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(App)
