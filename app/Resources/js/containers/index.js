import React from 'react'
import { connect } from 'react-redux'
import Timeline from '../components/timeline'
import Header from '../components/header'
import Footer from '../components/footer'
import Actions from '../actions/index'

import '../../sass/common/common.scss'
import '../../sass/page/index.scss'

class App extends React.Component {
    render() {
        return (
            <div>
                <Header
                    timelineDateList={this.props.timelineDateList}
                    appUsername={this.props.appUsername}
                    fetchDailyTweet={this.props.fetchDailyTweet}
                />
                <Timeline timelineJson={this.props.timelineJson}/>
                {/*<Footer/>*/}
            </div>
        )
    }
}

const mapStateToProps = (state) => (
    {
        timelineDateList: state.indexState.timelineDateList,
        timelineJson: state.indexState.timelineJson,
        appUsername: state.indexState.appUsername,
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
