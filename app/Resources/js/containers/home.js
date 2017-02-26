import React from 'react'
import { connect } from 'react-redux'
import ImportModal from '../components/import/importModal'
import ImportButton from '../components/import/importButton'
import Timeline from '../components/timeline'
import Header from '../components/header'
import Actions from '../actions/home'

import '../../sass/common/common.scss'
import '../../sass/page/home.scss'

class App extends React.Component {
    render() {
        return (
            <div>
                {/*<ImportButton import={this.props.import}/>*/}
                <ImportModal/>
                <Header
                    timelineDateList={this.props.timelineDateList}
                    username={this.props.username}
                    fetchDailyTweet={this.props.fetchDailyTweet}
                    isLogin={this.props.isLogin}
                />
                <Timeline/>
            </div>
        )
    }
}

const mapStateToProps = (state) => (
    {
        timelineDateList:       state.homeState.timelineDateList,
        timelineJson:           state.homeState.timelineJson,
        username:               state.homeState.username,
        isLogin:                state.homeState.isLogin
    }
)

function mapDispatchToProps(dispatch) {
    return {
        fetchDailyTweet: function (username, date) {
            dispatch(Actions.fetchDailyTweet(username, date))
        }
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(App)
