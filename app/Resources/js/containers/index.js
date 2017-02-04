import React from 'react'
import { connect } from 'react-redux'
import Timeline from '../components/timeline'
import Header from '../components/header'
import Actions from '../actions/index'

import '../../sass/common/common.scss'
import '../../sass/page/index.scss'

class App extends React.Component {
    
    // constructor(props, context) {
    //     super(props, context)
    //     this.state = {
    //         timelineJson: this.props.timelineJson
    //     }
    // }
    
    render() {
        return (
            <div>
                <Header
                    timelineDateList={this.props.timelineDateList}
                    appUsername={this.props.appUsername}
                    fetchDailyTweet={this.props.fetchDailyTweet}
                />
                <Timeline timelineJson={this.props.timelineJson}/>
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
